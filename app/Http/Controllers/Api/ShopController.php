<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acceptance;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index(){
        $shops=Shop::all();

        return response()->json([
            'data' => $shops
        ], 200);
    }

    public function show($id){
        $shop=Shop::findOrFail($id);
        $accept=Acceptance::where('shop_id', $id)
        ->where('user_id', Auth::user()->id)
        ->select('accept')
        ->first();

        return response()->json([
            'data' => $shop,
            'accept' => $accept
        ], 200);
    }
}
