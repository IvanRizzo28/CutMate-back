<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Shop;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function idShop($id){
        $employee=Shop::join('employee_shop', 'employee_shop.shop_id', 'shops.id')
        ->join('employees', 'employees.id', 'employee_shop.employee_id')
        ->join('users', 'users.id', 'employees.user_id')
        ->where('shops.id', $id)
        ->where('employee_shop.is_job', 1)
        ->select('employees.*', 'shops.name as shop', 'users.name', 'users.surname', 'employee_shop.role')
        ->get();

        return response()->json([
            "data" => $employee
        ],200);
    }
}
