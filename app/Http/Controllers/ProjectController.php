<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\StaffModel;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class ProjectController extends Controller
{
    function Show_order_list(Request $request){
        $Project = new Order;
        $Project = $Project::where("del_flg", 0)->orderBy("order_number")->get();
        return $Project;    
    }

    function Delete_order(Request $request){
        $Id_User_Login = $request['IDLoginUser'];
        $Id_Order = $request['Id_Order'];

        if(isset($Id_User_Login)){
            $Project = Order::where("id", $Id_Order)->first();
            $Project->update([
            "del_flg"=>1,
            "updated_user"=>$Id_User_Login,
            "updated_datetime"=>now()->setTimezone("Asia/Ho_Chi_Minh"),
        ]);
         return "Delete Order successfully";
        }   
    }
    
    const STATUS_LABELS = [
            0 => '実行中',
            1 => '非活性',
            2 => '保留',
            3 => '完了',
            4 => 'キャンセル'
    ];
    
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string',
            'order_number' => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'order_date'   => 'required|date',
            'status'       => 'required|integer|between:0,4',
            'order_income' => 'required|regex:/^[0-9]+$/',
            'internal_unit_price' => 'required|regex:/^[0-9]+$/',
            'IDLoginUser' => 'required|string',
        ], [
            'project_name.required' => 'project name is required',
            'project_name.regex'    => 'project name need to be string',
            'order_number.required' => 'project name is required',
            'client_name.required'  => 'client name is required',
            'order_date.date'       => 'order date need to be date',
            'status.required'       => 'status need is required',
            'order_income.required' => 'order income is required',
            'order_income.regex'    => 'order income need to be numeric',
            'internal_unit_price.required' => 'internal unit price is required',
            'internal_unit_price.regex'    => 'internal unit price need to be numeric',
            'IDLoginUser.required' => 'IDLoginUser is required'
        ]);
        
        if(isset($request->IDLoginUser)){
            $statusName = isset($request->status) ? self::STATUS_LABELS[$request->status] : null;
            $newProjectId = DB::table('t_projects')->insertGetId([                
            'project_name' => $request->project_name,
            'order_number' => $request->order_number,
            'client_name'  => $request->client_name,
            'order_date'   => $request->order_date,
            'status'       => $request->status,
            'order_income' => $request->order_income,
            'internal_unit_price' => $request->internal_unit_price,
            'del_flg' => 0,
            'created_user' => $request->IDLoginUser,
            'updated_user'=>$request->IDLoginUser,
            'created_datetime' => now()->setTimezone('Asia/Ho_Chi_Minh'),
            'updated_datetime' => now()->setTimezone('Asia/Ho_Chi_Minh'),
            ]);
            return response()->json([
                'message' => 'New Project ID is successfully inserted!',
                'project_id' => $newProjectId
            ], 201);
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ]);
        }
    }

    function Get_Order_By_ID(Request $request){
        $data = $request;
        $OrderID = $data['OrderID'];
        $Order = new Order;
        $Order = $Order::where("id", $OrderID)->first();
        return $Order;
    }

    function Order_Edit_Detail(Request $request){
        $Order = new Order;
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|regex:/^[\p{Han}]{2}$/u',
            'order_number' => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'order_date'   => 'required|date',
            'status'       => 'required|integer|between:0,4',
            'order_income' => 'required|regex:/^[0-9]+$/',
            'internal_unit_price' => 'required|regex:/^[0-9]+$/',
            'OrderID' => 'required|numeric',
            'IDLoginUser' => 'required|numeric',
        ], [
            'project_name.required' => 'project_name is required',
            'project_name.regex'    => 'project_name need to be string',
            'order_number.required' => 'order_number is required',
            'client_name.required'  => 'client_name is required',
            'order_date.date'       => 'order_date need to be date',
            'status.required'       => 'status is required',
            'order_income.required' => 'order_income is required',
            'order_income.regex'    => 'order_income need to be numeric',
            'internal_unit_price.required' => 'internal_unit_price is required',
            'internal_unit_price.regex'    => 'internal_unit_price need to be numeric',
            'OrderID.required'=>"OrderID is required",
            "IDLoginUser.required"=>"IDLoginUser is required"
        ]);
        if ($validator->fails()){
            return $validator->errors();
        }
        else{
            if (isset($request->IDLoginUser)){
                $statusName = isset($request->status) ? self::STATUS_LABELS[$request->status] : null;
                $Order = $Order::where('id', $request->OrderID)->first();
                $Order->update([
                    'project_name'=>$request->project_name,
                    'order_number'=>$request->order_number,
                    'client_name'=>$request->client_name,
                    'order_date'=>$request->order_date,
                    'status'=>$request->status,  
                    'order_income'=>$request->order_income,
                    'internal_unit_price'=>$request->internal_unit_price,
                    "del_flg"=>0,
                    'updated_user'=>$request->IDLoginUser,
                    'updated_datetime'=>now(),
                ]);
                return "Edited Project Successfully";
            }
        }
    }

    function HandleSearchOrder(Request $request)
    {
        $request->validate([
            'order_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'client_name'  => 'nullable|string|max:255',
            'status'       => 'nullable|integer|between:0,4'
        ]);

        $IDLoginUser = $request->IDLoginUser;

        if (isset($IDLoginUser)){
            $orderNumber = $request->input('order_number');
            $projectName = $request->input('project_name');
            $clientName  = $request->input('client_name');
            $status      = $request->input('status');

            $statusName = isset($status) ? self::STATUS_LABELS[$status] : null;

            $query = DB::table('t_projects')
                ->where('del_flg', 0);

            if ($orderNumber) {
                $query->where('order_number', 'LIKE', "%$orderNumber%");
            }

            if ($projectName) {
                $query->where('project_name', 'LIKE', "%$projectName%");
            }

            if ($clientName) {
                $query->where('client_name', 'LIKE', "%$clientName%");
            }

            if (isset($status)) {
                $query->where('status', $status);
            }

            $projects = $query->orderBy('order_number', 'ASC')->get();

            return $projects;
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ]);
        }
    }
}
