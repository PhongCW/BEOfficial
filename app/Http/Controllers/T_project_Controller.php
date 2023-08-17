<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\t_project_model;
use App\Models\t_project;
use App\Models\Order;

class T_project_Controller extends Controller
{
    function Actual_Plan(Request $request){
        $ID_order = $request['order_id'];
        $order = new Order;
        $order = $order::where("id",$ID_order)->first();
        $order_number = $order['order_number'];
        $client_name = $order['client_name'];
        $project_name = $order['project_name'];
        $internal_unit_price = $order['internal_unit_price'];
        $order_income_A = $order['order_income'];
        $order_income_B = $order_income_A*0.9;
        return $order_income_B;
    }
}
