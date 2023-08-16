<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\t_project_model;
use App\Models\t_project;

// HERE FOR HOMEWORK
class T_project_Controller extends Controller
{
    function Actual_Plan(Request $request){
        $Order = $request;
        $id_from_order_list = $Order['id_order'];
        $t_project_all = new t_project;
        $t_project = $t_project_all::where("id", $id_from_order_list)->first();
        $order_income_all = $t_project_all::where("id", $id_from_order_list)->get("order_income");
        $order_number = $t_project['order_number'];
        $client_name = $t_project['client_name'];
        $order_income = $t_project['order_income'];
        $project_name = $t_project['project_name'];
        $order_value = $order_income*0.9;
        
    }
}
