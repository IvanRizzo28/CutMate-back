<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function idShop($id){
        $services=Service::join('shops', 'shops.id', 'services.shop_id')
        ->where('shops.id', $id)
        ->select('services.*')
        ->get();

        return response()->json([
            'data' => $services
        ], 200);
    }
}
