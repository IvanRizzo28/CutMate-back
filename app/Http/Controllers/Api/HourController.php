<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Closing;
use App\Models\Day;
use App\Models\Hour;
use App\Models\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HourController extends Controller
{

    private $dayWeek = ['dom', 'lun', 'mar', 'mer', 'gio', 'ven', 'sab'];
    private $arrayOrari = [];

    private function getHours($idShop, $date, $idEmployee)
    {
        $this->initializeArray();
        $hours = Hour::where('shop_id', $idShop)
            ->where('date', $date)
            ->first();

        if (!$hours) $hours = Shop::findOrFail($idShop);

        $this->setArray($hours->opening_hour_morning, $hours->closing_hour_morning, $hours->opening_hour_afternoon, $hours->closing_hour_afternoon, $idEmployee, $date, $idShop);
    }

    private function initializeArray()
    {
        for ($i = 0; $i < 288; $i++) $this->arrayOrari[] = 2;
    }

    private function setArray($aperturaMattina, $chiusuraMattina, $aperturaPomeriggio, $chiusuraPomeriggio, $idEmployee, $date, $idShop)
    {
        //mattina
        $aMOra = intval(substr($aperturaMattina, 0, 2));
        $aMM = intval(substr($aperturaMattina, 3));
        $cMora = intval(substr($chiusuraMattina, 0, 2));
        $cMM = intval(substr($chiusuraMattina, 3));
        //pomeriggio
        $aPOra = intval(substr($aperturaPomeriggio, 0, 2));
        $aPM = intval(substr($aperturaPomeriggio, 3));
        $cPora = intval(substr($chiusuraPomeriggio, 0, 2));
        $cPM = intval(substr($chiusuraPomeriggio, 3));
        //calcolo l'indici
        $index[] = ($aMOra * 12) + ($aMM / 5);
        $index[] = ($cMora * 12) + ($cMM / 5);
        $index[] = ($aPOra * 12) + ($aPM / 5);
        $index[] = ($cPora * 12) + ($cPM / 5);

        for ($i = $index[0]; $i < $index[1]; $i++) $this->arrayOrari[$i] = 0;
        for ($i = $index[2]; $i < $index[3]; $i++) $this->arrayOrari[$i] = 0;

        //aggiungo prenotazioni
        $prenotazioni = Booking::join('services', 'services.id', 'bookings.service_id')
            ->where('date', $date)
            ->where('employee_id', $idEmployee)
            ->where('bookings.shop_id', $idShop)
            ->get();

        //return $prenotazioni;
        foreach ($prenotazioni as $value) {
            for ($i = $value['index']; $i < $value['index'] + $value['time']; $i++) $this->arrayOrari[$i] = $value['type'];
        }
    }

    public function controll($idShop, $date, $idEmployee)
    {
        $dataOdierna = Carbon::now();

        if ($date >= $dataOdierna->format('Y-m-d')) {
            //query per vedere se esiste nella tabella days lo shop per la specifica data
            $open = Day::join('shops', 'shops.id', 'days.shop_id')
                ->where('days.date', $date)
                ->select('days.*')
                ->first();

            //se esiste ed è aperto allora si ritornano gli orari
            if ($open && $open['is_close'] == 0) {
                $this->getHours($idShop, $date, $idEmployee);

                return $this->arrayOrari;
            } else if ($open && $open['is_close'] == 1) return 'Chiuso';
            else {
                //se non esiste si va a controllare nella tabella closings se c'è un giorno di chiusura corrispondente, se si trova si ritorna un errore
                $tmp = Carbon::createFromFormat('Y-m-d', $date);
                $tmp->locale('it')->shortDayName;
                $closing = Closing::join('shops', 'shops.id', 'closings.shop_id')
                    ->where('closings.day', $this->dayWeek[$tmp->dayOfWeek])
                    ->first();

                if (!$closing) {
                    $this->getHours($idShop, $date, $idEmployee);

                    return $this->arrayOrari;
                } else return 'Chiuso';
            }
        } else return 'Errore';
    }

    public function idShopDate($idShop, $date, $idEmployee)
    {
        $check = $this->controll($idShop, $date, $idEmployee);
        if (is_array($check)) return response()->json(['data' => $check], 200);
        return abort(401, $check);
    }

    //----------------------------------Parte del codice che prima stava all'interno di idShopDate------------------------------------------------------------
    /*$dataOdierna = Carbon::now();

        if ($date >= $dataOdierna->format('Y-m-d')) {
            //query per vedere se esiste nella tabella days lo shop per la specifica data
            $open = Day::join('shops', 'shops.id', 'days.shop_id')
                ->where('days.date', $date)
                ->select('days.*')
                ->first();

            //se esiste ed è aperto allora si ritornano gli orari
            if ($open && $open['is_close'] == 0) {
                $this->getHours($idShop, $date, $idEmployee);

                return response()->json([
                    'data' => $this->arrayOrari
                ], 200);
            } else if ($open && $open['is_close'] == 1) return abort(401, 'Chiuso');
            else {
                //se non esiste si va a controllare nella tabella closings se c'è un giorno di chiusura corrispondente, se si trova si ritorna un errore
                $tmp = Carbon::createFromFormat('Y-m-d', $date);
                $tmp->locale('it')->shortDayName;
                $closing = Closing::join('shops', 'shops.id', 'closings.shop_id')
                    ->where('closings.day', $this->dayWeek[$tmp->dayOfWeek])
                    ->first();

                if (!$closing) {
                    $this->getHours($idShop, $date, $idEmployee);

                    return response()->json([
                        'data' => $this->arrayOrari
                    ], 200);
                } else return abort(401, 'Chiuso');
            }
        } else return abort(401, 'Errore');*/
}
