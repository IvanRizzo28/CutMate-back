<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;

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

        return response()->json([
            'data' => $shop
        ], 200);
    }
}
