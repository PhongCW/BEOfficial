<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\StaffModel;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class ProjectController extends Controller
{
    function Show_order_list(Request $request){
        $Project = new Order;
        $Project = $Project::where("del_flg", 0)->orderBy("order_number")->orderBy("created_datetime", "DESC")->get();
        return $Project;    
    }

    function Delete_order(Request $request){
        $IDLoginUser = $request['IDLoginUser'];
        // $IDLoginUser = session("IDLoginUser");
        $Id_Order = $request['Id_Order'];
        $User = User::where("id", $IDLoginUser)->first();
        if(isset($User)){
            Auth::login($User);
            $Project = Order::where("id", $Id_Order)->first();
            $Project->update([
            "del_flg"=>1,
            "updated_user"=>Auth::user()->id,
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
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|regex:/^[a-zA-Z0-9 -~\p{Hiragana}\p{Katakana}\p{Han}０１２３４５６７８９]{0,255}$/u',   // 1 byte
            'order_number' => 'required|string|max:255',
            'client_name'  => 'required|regex:/^[a-zA-Z0-9 -~\p{Hiragana}\p{Katakana}\p{Han}０１２３４５６７８９]{0,255}$/u',    // 1 byte
            'order_date'   => 'required|date',
            'status'       => 'required|integer|between:0,4',
            'order_income' => 'required|regex:/^[0-9]+$/',          // 1 byte
            'internal_unit_price' => 'required|regex:/^[0-9]+$/',   // 1 byte
        ], [
            'project_name.required' => 'project name is required',
            'order_number.required' => 'project name is required',
            'client_name.required'  => 'client name is required',
            'order_date.date' => 'order date need to be date',
            'status.required'       => 'status need is required',
            'order_income.required' => 'order income is required',
            'order_income.regex'    => 'Only input 1 byte number', // show new message
            'internal_unit_price.required' => 'internal unit price is required',
            'internal_unit_price.regex'    => 'Only input 1 byte number', // show new message
        ]);

        if ($validator->fails()){
            return $validator->errors();
        }
        
        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();
        if(isset($User)){
            Auth::login($User);
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
            'created_user' => Auth::user()->id,
            'updated_user'=>Auth::user()->id,
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
        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();
        Auth::login($User);
        if (isset($User)){
            $OrderID = $data['OrderID'];
            $Order = new Order;
            $Order = $Order::where("id", $OrderID)->first();
            return $Order;
        }
        else{
            return response()->json([
                "message"=>"You haven't not login yet"
            ], 404);
        }
    }

    function Order_Edit_Detail(Request $request){
        $Order = new Order;
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|regex:/^[a-zA-Z0-9 -~\p{Hiragana}\p{Katakana}\p{Han}０１２３４５６７８９]{0,255}$/u', // 1byte
            'order_number' => 'required|string|max:255',
            'client_name'  => 'required|regex:/^[a-zA-Z0-9 -~\p{Hiragana}\p{Katakana}\p{Han}０１２３４５６７８９]{0,255}$/u', // 1byte
            'order_date' => 'required|date',
            'status'       => 'required|integer|between:0,4',
            'order_income' => 'required|regex:/^[0-9]+$/',      // 1byte
            'internal_unit_price' => 'required|regex:/^[0-9]+$/', // 1byte 
            'OrderID' => 'required|numeric',

        ], [
            'project_name.required' => 'project_name is required',
            'order_number.required' => 'order_number is required',
            'client_name.required'  => 'client_name is required',
            'order_date.date'  => 'order_date need to be year, month and day',
            'status.required'       => 'status is required',
            'order_income.required' => 'order_income is required',
            'order_income.regex'    => 'Only input 1 byte number', // show new message
            'internal_unit_price.required' => 'internal_unit_price is required',
            'internal_unit_price.regex'    => 'Only input 1 byte number', // show new message
            'OrderID.required'=>"OrderID is required",
        ]);
        if ($validator->fails()){
            return $validator->errors();
        }
        else{
            $IDLoginUser = $request->IDLoginUser;
            // $IDLoginUser = session("IDLoginUser");
            $User = User::where("id", $IDLoginUser)->first();
            if (isset($User)){
                Auth::login($User);
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
                    'updated_user'=>Auth::user()->id,
                    'updated_datetime'=>now(),
                ]);
                return response()->json([
                    "message"=>"Edited Project Successfully"
                ]);
            }
        }
    }

    function HandleSearchOrder(Request $request)
    {

        $Check = Validator::make($request->all(), [
            'order_number' => 'nullable|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'client_name'  => 'nullable|string|max:255',
            'status'       => 'nullable|integer|between:0,4'
        ]);
        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();
        if (isset($User)){
            Auth::login($User);
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

            $projects = $query->orderBy('order_number', 'DESC')->get();

            return $projects;
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ]);
        }
    }
}
