<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|max:70|min:5',
            'password' => 'required|string|max:20|min:5'
        ]);
        $data=$request->all();
        $rem=false;

        if ($data['remember'] != ''){ //mettere && isset($data['remember'])
            $rem=$data['remember'];
        } else if (!isset($data['remember'])) $rem=false;
        unset($data['remember']);

        if (!Auth::attempt($data, $rem)){
            /*return response()->json([
                'message' => 'Credenziali errate'
            ],422);*/
            abort('401', 'login-failed');
        }

        $user= Auth::user();
        $token=$user->createToken('APIToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|max:70|min:5|unique:users,email',
            'password' => 'required|string|max:20|min:5',
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50'
        ]);

        $data=$request->all();

        $newUser=User::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token=$newUser->createToken('APIToken')->plainTextToken;

        return response()->json([
            'user' => $newUser,
            'token' => $token
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response(null, 204);
        //return response()->json([ "user" => Auth::user()]);
    }
}
