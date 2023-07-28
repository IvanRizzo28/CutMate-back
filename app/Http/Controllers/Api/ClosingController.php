<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Closing;
use Illuminate\Http\Request;

class ClosingController extends Controller
{
    public function idShop($id){
        $closing=Closing::join('shops', 'shops.id', 'closings.shop_id')
        ->where('shops.id', $id)
        ->select('closings.day')
        ->get();

        return response()->json([
            'data' => $closing
        ], 200);
    }
}
