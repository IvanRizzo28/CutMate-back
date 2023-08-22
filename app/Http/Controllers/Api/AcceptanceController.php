<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acceptance;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptanceController extends Controller
{
    public function create($idShop){
        Shop::findOrFail($idShop);
        $newAcceptance=new Acceptance();
        $newAcceptance->shop_id=$idShop;
        $newAcceptance->user_id=Auth::user()->id;
        $newAcceptance->save();

        return response()->json([
            'data' => $newAcceptance
        ], 200);
    }
}
