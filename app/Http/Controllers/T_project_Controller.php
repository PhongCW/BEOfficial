<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\t_project_model;
use App\Models\t_project;
use App\Models\Order;
use App\Models\Staff;
use App\Models\StaffModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class T_project_Controller extends Controller
{
    function Actual_Plan(Request $request){

        // Datas are filled out
        $project_id = $request['order_id']; // t_project id (order)

        // Here print out data nescessary base on order list (t_project)
        $order = new Order;
        $order = $order::where("id",$project_id)->first();
        $order_number = $order['order_number'];
        $client_name = $order['client_name'];
        $project_name = $order['project_name'];
        $internal_unit_price = $order['internal_unit_price'];
        $order_income_A = $order['order_income'];

        // Calculate order_income_B base on order_income_A
        $order_income_B = $order_income_A*0.9;
        
        return response()->json([
            "order_number"=>$order_number,
            "client_name"=>$client_name,
            "project_name"=>$project_name,
            "internal_unit_price"=>$internal_unit_price,
            "order_income_A"=>$order_income_A,
            "order_income_B"=>$order_income_B
        ]);
    }

    function Get_Staff(Request $request){

        $StaffArray = [];
        $Stafftype = [];

        $StaffModel = new StaffModel;
        $StaffModel = $StaffModel::all();

        foreach ($StaffModel as $StaffItem) {
            $Staff_Last_Name = $StaffItem['last_name'];
            $Staff_First_Name = $StaffItem['first_name'];
            array_push($StaffArray, $Staff_Last_Name.$Staff_First_Name);
        }
        foreach ($StaffModel as $Staff_Type) {
            array_push($Stafftype, $Staff_Type['staff_type']);
        }

        $result = array_map(null, $StaffArray, $Stafftype);
 
        return response()->json([
            "Staff and Stafftype" => $result
        ], 200); 
    }

    function Register(Request $request){
        $Data = Validator::make($request->all(), [
            "Staff"=>"required",
            "StaffType"=>"required",
        ], [
            "Staff.required"=>"Staff is required",
            "StaffType.required"=>"StaffType is required"
        ]);
        if($Data->fails()){
            return $Data->errors();
        }
        else{
            $users = DB::table('m_staffs_data')
            ->select(DB::raw('CONCAT(last_name, first_name) as full_name'))
            ->get();
            return $users;
        }
    }
}
