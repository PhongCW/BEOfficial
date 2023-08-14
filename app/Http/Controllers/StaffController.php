<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffModel;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{
    function Show_Staff_Screen(Request $request){
        $Staff = new Staff;
        $Staff = $Staff::where("del_flg", 0)->orderBy('created_datetime')->get();
        return $Staff;
    }
    function Delete(Request $request){
        $SelectedStaff = $request;
        $SelectedStaffID = $SelectedStaff['id']; //here id staff
        $ID_Login = $SelectedStaff['id_login']; //need IDlogin from user entered
        $SelectedStaff_Condition = $SelectedStaff['Condition']; // when click to verify, send true!

        $Staff = new Staff;
        $Staff = $Staff::where("id", $SelectedStaffID)->first();

        if ($SelectedStaff_Condition == true){
            $Staff->update([
                'del_flg' => 1,
                'updated_user'=> $ID_Login,
                'updated_datetime'=> now(),
            ]);
        }
    }
    function Staff_Create(Request $request){
        $Staff_Create = Validator::make($request->all(), [
            'last_name'=>"required|nullable|string|size:2",
            'first_name'=>"required|nullable|string|size:2",
            'last_name_furigana'=>'required|nullable|string',
            'first_name_furigana'=>'required|nullable|string',
            'office' => 'required|nullable',
        ], [
            'last_name.required' => "Last name is required",
            'first_name.required' => "First name is required",
            'last_name_furigana.required'=> "Last name furigana is required",
            "first_name_furigana.required" => "first name furigana is required",
            'office.required' =>'Office is required!',
        ]);
        if ($Staff_Create->fails()) {
            return $Staff_Create->errors();
        }
        else{
            $IDLoginUser = $request['IDLoginUser'];
            $Condtion_verify = $request['Condtion_verify'];
            $Condition_menu = $request['Condition_menu'];
            $Condition_Staff_List = $request['Condition_Staff_List'];

            $Data = $Staff_Create->getData();

            if($Condtion_verify == true){
                $StaffModel = new StaffModel;

                $StaffModel['last_name'] = $Data['last_name'];
                $StaffModel['first_name'] = $Data['first_name'];
                $StaffModel['last_name_furigana'] = $Data['last_name_furigana'];
                $StaffModel['first_name_furigana'] = $Data['first_name_furigana'];
                $StaffModel['office'] = $Data['office'];
                $StaffModel['staff_type'] = 0;
                $StaffModel['del_flg'] = 0;
                $StaffModel['created_user'] = $IDLoginUser;
                $StaffModel['created_datetime'] = now();
                $StaffModel['updated_user'] = $IDLoginUser;
                $StaffModel['updated_datetime'] = now();

                $StaffModel -> save();
                return "New Staff is created";
            }
            if($Condition_menu == true){
                $StaffModel = new StaffModel;

                $StaffModel['last_name'] = $Data['last_name'];
                $StaffModel['first_name'] = $Data['first_name'];
                $StaffModel['last_name_furigana'] = $Data['last_name_furigana'];
                $StaffModel['first_name_furigana'] = $Data['first_name_furigana'];
                $StaffModel['office'] = $Data['office'];
                $StaffModel['staff_type'] = 0;
                $StaffModel['del_flg'] = 0;
                $StaffModel['created_user'] = $IDLoginUser;
                $StaffModel['created_datetime'] = now();
                $StaffModel['updated_user'] = $IDLoginUser;
                $StaffModel['updated_datetime'] = now();

                $StaffModel -> save();
                return "New Staff is created";
            }
            if($Condition_Staff_List == true){
                $StaffModel = new StaffModel;

                $StaffModel['last_name'] = $Data['last_name'];
                $StaffModel['first_name'] = $Data['first_name'];
                $StaffModel['last_name_furigana'] = $Data['last_name_furigana'];
                $StaffModel['first_name_furigana'] = $Data['first_name_furigana'];
                $StaffModel['office'] = $Data['office'];
                $StaffModel['staff_type'] = 0;
                $StaffModel['del_flg'] = 0;
                $StaffModel['created_user'] = $IDLoginUser;
                $StaffModel['created_datetime'] = now();
                $StaffModel['updated_user'] = $IDLoginUser;
                $StaffModel['updated_datetime'] = now();

                $StaffModel -> save();
                return "New Staff is created";
            }
        } 
    }
    function Staff_Detail_Edit(Request $request){
        $Staff_Edit = Validator::make($request->all(),[
            'last_name'=>"required|string|nullable|size:2",
            'first_name'=>"required|string|nullable|size:2",
            'last_name_furigana'=>"required|string|nullable",
            'first_name_furigana'=>"required|string|nullable",
            'office'=>"required|string",
        ],[
            'last_name.required' => "Last name is required",
            'first_name.required' => "First name is required",
            'last_name_furigana.required'=> "Last name furigana is required",
            "first_name_furigana.required" => "first name furigana is required",
            'office.required' =>'Office is required!',
        ]);
        if ($Staff_Edit->fails()){
            return $Staff_Edit->errors();
        }
        else{
            $Staff_Edit_Data = $Staff_Edit->getData();
            $Staff = new Staff;

            $IDLoginUser = $request['IDLoginUser'];
            $ID_Staff_Edit = $request['ID_Staff_Edit'];
            $Condition_verify = $request['Condition_verify'];
            $Condition_menu = $request['Condition_menu'];
            $Condition_staff_list = $request['Condition_staff_list'];

            if($Condition_verify == true){
                $Staff_Find_In_Model = Staff::where("id", $ID_Staff_Edit)->first();
                $Staff_Find_In_Model->update([
                    "last_name" => $Staff_Edit_Data['last_name'],
                    "first_name" => $Staff_Edit_Data['first_name'],
                    "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                    "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                    "office" => $Staff_Edit_Data['office'],
                    'staff_type' => 0,
                    'del_flg' => 0,
                    'updated_user' => $IDLoginUser,
                    'updated_datetime' => now(),
                ]);
                return "Staff is edited";
            }
            if($Condition_menu == true){
                $Staff_Find_In_Model = $Staff::where("id", $ID_Staff_Edit)->first();
                $Staff_Find_In_Model->update([
                    "last_name" => $Staff_Edit_Data['last_name'],
                    "first_name" => $Staff_Edit_Data['first_name'],
                    "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                    "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                    "office" => $Staff_Edit_Data['office'],
                    'staff_type' => 0,
                    'del_flg' => 0,
                    'updated_user' => $IDLoginUser,
                    'updated_datetime'=> now(),
                ]);
                return "Staff is edited";
            }
            if($Condition_staff_list == true){
                $Staff_Find_In_Model = $Staff::where("id", $ID_Staff_Edit)->first();
                $Staff_Find_In_Model->update([
                    "last_name" => $Staff_Edit_Data['last_name'],
                    "first_name" => $Staff_Edit_Data['first_name'],
                    "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                    "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                    "office" => $Staff_Edit_Data['office'],
                    'staff_type' => 0,
                    'del_flg' => 0,
                    'updated_user' => $IDLoginUser,
                    'updated_datetime' => now(),
                ]);
                return "Staff is edited";
            }
        }
    }
}
