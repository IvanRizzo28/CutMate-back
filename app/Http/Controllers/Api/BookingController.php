<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function booking(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'employee_id' => 'required|exists:employees,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'index' => 'required|min:0|max:287'
        ]);

        $data = $request->all();
        //-------------------------controlli---------------------------------------------

        //controllo per vedere se l'employee esiste o lavora nel determinato shop
        $controllo = Employee::join('employee_shop', 'employee_shop.employee_id', 'employees.id')
            ->join('shops', 'shops.id', 'employee_shop.shop_id')
            ->where('shops.id', $data['shop_id'])
            ->where('employees.id', $data['employee_id'])
            ->where('employee_shop.is_job', 1)
            ->orderBy('employee_shop.updated_at', 'DESC')
            ->first();
        if (!$controllo) return abort(401, 'Errore');

        //controllo per vedere se il servizio è presente nello shop
        $controllo2=Service::join('shops', 'shops.id', 'services.shop_id')
        ->where('shops.id', $data['shop_id'])
        ->where('services.id', $data['service_id'])
        ->select('services.*')
        ->first();
        if(!$controllo2) return abort(401, 'Errore');

        //controllo per vedere se effettivamente c'è lo spazio libero per la prenotazione
        $controller=new HourController();
        $arrayOrari=$controller->controll($data['shop_id'], $data['date'], $data['employee_id']);
        if($data['index'] <= 288 - $controllo2->time && $arrayOrari[$data['index']] == 0){
            $count=$data['index'];
            while($count<$data['index'] + $controllo2->time){
                if($arrayOrari[$count] > 0) return abort(401, 'Orario occupato');
                $count++;
            }
        } else abort(401, 'Errore222');

        //-----------------------------fine controlli----------------------------------------------

        $data['user_id'] = Auth::user()->id;
        $booking = Booking::create($data);

        return response()->json([
            'data' => $booking
        ], 200);
    }
}
