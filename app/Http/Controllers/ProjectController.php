<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\StaffModel;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    function Show_order_list(Request $request){
        $Project = new Order;
        $Project = $Project::where("del_flg", 0)->orderBy("order_number")->get();
        return $Project;    
    }

    function Delete_order(Request $request){
        $Id_User_Login = $request['Id_User_Login'];
        $Id_Order = $request['Id_Order'];

        $Project = Order::where("id", $Id_Order)->first();
            $Project->update([
            "del_flg"=>1,
            "updated_user"=>$Id_User_Login,
            "updated_datetime"=>now()->setTimezone("Asia/Ho_Chi_Minh"),
        ]);
         return "Delete Order successfully";
    }
        
        // $status_all = ['実行中','非活性','保留','完了','キャンセル '];
        // $Validator = new Validator;
        // $Validate = Validator::make($request->all(), [
        //     'project_name'=>'required',
        //     'order_number'=>'required',
        //     'client_name'=>'required',
        //     'order_date'=>'required',
        //     'status'=>'required',
        //     'order_income'=>'required',
        //     'internal_unit_price'=>'required',
        //     'IDLoginUser'=>'required',
        // ]);
        // if ($Validate -> fails()){
        //     return $Validate->errors();
        // }
        // else{
        //     $OrderModel = new OrderModel;
        //     $Valid = $Validate->getData();
        //     $OrderModel['project_name'] = $Valid['project_name'];
        //     $OrderModel['order_number'] = $Valid['order_number'];
        //     $OrderModel['client_name'] = $Valid['client_name'];
        //     $OrderModel['order_date'] = $Valid['order_date'];
        //     $OrderModel['order_income'] = $Valid['order_income'];
        //     $OrderModel['internal_unit_price'] = $Valid['internal_unit_price'];
        //     $OrderModel['created_user'] = $Valid['IDLoginUser'];
        //     $OrderModel['status'] = $Valid['status'];
        //     $OrderModel['created_datetime'] = now();
        //     $OrderModel['updated_user'] = $Valid['IDLoginUser'];
        //     $OrderModel['updated_datetime'] = now();
        //     $OrderModel -> save();
        //     return "Success";
        // }
    
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
    
        $statusName = isset($request->status) ? self::STATUS_LABELS[$request->status] : null;
        $newProjectId = DB::table('t_projects')->insertGetId([                'project_name' => $request->project_name,
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

    function Get_Order_By_ID(Request $request){
        $data = $request;
        $OrderID = $data['OrderID'];
        $Order = new Order;
        $Order = $Order::where("id", $OrderID)->first();
        return $Order;
    }

    // Edit Order đang check
    function Order_Edit_Detail(Request $request){
        $request->validate([
            'project_name' => 'required|string',
            'order_number' => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'order_date'   => 'required|date',
            'status'       => 'required|integer|between:0,4',
            'order_income' => 'required|regex:/^[0-9]+$/',
            'internal_unit_price' => 'required|regex:/^[0-9]+$/',
            'IDLoginUser' => 'required|string',
            'OrderID' => 'required|string'
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
            'IDLoginUser.required' => 'IDLoginUser is required',
            'OrderID.required' => "OrderID is required"
        ]);
        $statusName = isset($request->status) ? self::STATUS_LABELS[$request->status] : null;
        $newProjectId = DB::table('t_projects')->where("id", $request->OrderID)->first()->update([                
            'project_name' => $request->project_name,
            'order_number' => $request->order_number,
            'client_name'  => $request->client_name,
            'order_date'   => $request->order_date,
            'status'       => $request->status,
            'order_income' => $request->order_income,
            'internal_unit_price' => $request->internal_unit_price,
            'del_flg' => 0,
            'updated_user'=>$request->IDLoginUser,
            'updated_datetime' => now()->setTimezone('Asia/Ho_Chi_Minh'),
        ]);
        return response()->json([
            'message' => 'Project ID is Edited successfully',
            'project_id' => $newProjectId
        ], 201);
    }
    function Handle(Request $request)
    {
        $request->validate([
            'order_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'client_name'  => 'nullable|string|max:255',
            'status'       => 'nullable|integer|between:0,4'
        ]);

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

}
