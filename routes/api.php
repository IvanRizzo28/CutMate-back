<?php

use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ClosingController;
use App\Http\Controllers\Api\DayController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\HourController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//rotta per il ricordami del login
Route::middleware('auth:sanctum')->get('/user', function () {
    $user = Auth::user();
    $token = $user->createToken('APIToken')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
});

//shops
Route::middleware('auth:sanctum')->get('/shops', [ShopController::class, 'index']);
Route::middleware('auth:sanctum')->get('/shop/{id}', [ShopController::class, 'show']);

//employees
Route::middleware('auth:sanctum')->get('/employees/{idShop}', [EmployeeController::class, 'idShop']);

//services
Route::middleware('auth:sanctum')->get('/services/{idShop}', [ServiceController::class, 'idShop']);

//closing days
Route::middleware('auth:sanctum')->get('/closing/{idShop}', [ClosingController::class, 'idShop']);

//specific days
Route::middleware('auth:sanctum')->get('/days/{idShop}/{date}', [DayController::class, 'idShop']);

//orari per giorno specifico ritorna un array di 288 elementi con all'interno 0,1 o 2
Route::middleware('auth:sanctum')->get('/orari/{idShop}/{date}/{idEmployee}', [HourController::class, 'idShopDate']);

//prenotazione
Route::middleware('auth:sanctum')->post('/booking', [BookingController::class, 'booking']);

//login, registrazione, logout
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::delete('/logout', [UserController::class, 'logout']);
