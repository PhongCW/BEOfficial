<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    function Show_order_list(Request $request){
        $Project = new Project;
        $Project = $Project::where("del_flg", 0)->orderBy("order_number")->get();
        return $Project;    
    }

    function Delete_order(Request $request){
        $Id_User_Login = $request['Id_User_Login'];
        $Id_Order = $request['Id_Order'];
        $Condition_verify = $request['Condition_verify'];

        if ($Condition_verify == true){
            $Project = Project::where("id", $Id_Order)->first();
                $Project->update([
                "del_flg"=>1,
                "updated_user"=>$Id_User_Login,
                "updated_datetime"=>now()->setTimezone("Asia/Ho_Chi_Minh"),
            ]);
            return "Delete Order successfully";
        }
    }
    function Order_Create(Request $request){
        $Order_Create = Validator::make($request->all(), [
            "project_name" => "required|string|nullable",
            "order_number" => "required|string|nullable",
            "client_name" => "required|string|nullable",
            "order_date" => "required|date|nullable",
            "status" => "required|nullable",
            "order_income" => "required|numeric",
            "internal_unit_price" => "required|numeric",
        ],[
            "project_name.required" => "Project name is required",
            "order_number.required" => "Order number is required",
            "client_name.required" => "Client name is required",
            "order_date.required" => "Order date is required",
            "status.required" => "Status is required",
            "order_income.required" => "Order income is required",
            "internal_unit_price.required" => "Internal Unit Price is required"
        ]);
        
        if ($Order_Create->fails()){
            return $Order_Create -> errors();
        }
        //Gonna check using frontend;
        else{
            $data = $Order_Create->getData();
            $Data_post = $request;
            $Status_all_post = $Data_post['status_all'];
            $IDLoginUser = $Data_post['IDLoginUser'];
            $Condition_verify = $Data_post['Condition_verify'];
            $Condition_menu = $Data_post['Condition_menu'];
            $Condition_staff_list = $Data_post['Condition_staff_list'];

            if($Condition_verify == true){
                if($data['status']== $Status_all_post[0]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 0;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[1]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 1;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[2]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 2;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[3]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 3;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[4]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 4;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
            }
            if ($Condition_menu == true){
                if($data['status']== $Status_all_post[0]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 0;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[1]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 1;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[2]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 2;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[3]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 3;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[4]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 4;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
            }
            if($Condition_staff_list == true){
                if($data['status']== $Status_all_post[0]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 0;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[1]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 1;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[2]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 2;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if($data['status'] == $Status_all_post[3]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 3;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
                if ($data['status'] == $Status_all_post[4]){
                    $ProjectModel = new ProjectModel;
                    $ProjectModel['project_name'] = $data['project_name'];
                    $ProjectModel['order_number'] = $data['order_number'];
                    $ProjectModel['client_name'] = $data['client_name'];
                    $ProjectModel['order_date'] = $data['order_date'];
                    $ProjectModel['status'] = 4;
                    $ProjectModel['order_income'] = $data['order_income'];
                    $ProjectModel['internal_unit_price'] = $data['internal_unit_price'];
                    $ProjectModel['del_flg'] = 0;
                    $ProjectModel['created_user'] = $IDLoginUser;
                    $ProjectModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                    $ProjectModel['updated_user'] = $IDLoginUser;
                    $ProjectModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
    
                    $ProjectModel -> save();
                    return "New Project is saved successfully";
                }
            }
        }
    }
}
