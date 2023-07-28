<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DayController extends Controller
{
    public function idShop($id,$dateInitial){
        $date=Carbon::createFromFormat('Y-m-d', $dateInitial);
        $date2=Carbon::createFromFormat('Y-m-d', $dateInitial);
        $date2->addDay(10);

        $days=Day::join('shops', 'shops.id', 'days.shop_id')
        ->where('shops.id', $id)
        ->whereBetween('date', [$date->format('Y-m-d'), $date2->format('Y-m-d')])
        ->select('days.*')
        ->get();

        return response()->json([
            'data' => $days
        ]);
    }
}
