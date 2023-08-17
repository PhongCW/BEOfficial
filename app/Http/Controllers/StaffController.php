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
    function GetStaffById(Request $request){
        $data = $request->all();
        $id = $data['StaffID'];
        $Staff = new Staff;
        $Staff = $Staff::where("id", $id)->first();
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
                'updated_datetime'=> now()->setTimezone("Asia/Ho_Chi_Minh"),
            ]);
            return "Deleted Successfully";
        }
    }
    function Staff_Create(Request $request){
        $Staff_Create = Validator::make($request->all(), [
            'last_name'=>"required|nullable|string|size:2",
            'first_name'=>"required|nullable|string|size:2",
            'last_name_furigana'=>'required|nullable|string',
            'first_name_furigana'=>'required|nullable|string',
        ], [
            'last_name.required' => "Last name is required",
            'first_name.required' => "First name is required",
            'last_name_furigana.required'=> "Last name furigana is required",
            "first_name_furigana.required" => "first name furigana is required",
        ]);
        if ($Staff_Create->fails()) {
            return $Staff_Create->errors();
        }
        else{
            $IDLoginUser = $request['IDLoginUser'];
            $Data = $Staff_Create->getData();

            $StaffModel = new StaffModel;

                $StaffModel['last_name'] = $Data['last_name'];
                $StaffModel['first_name'] = $Data['first_name'];
                $StaffModel['last_name_furigana'] = $Data['last_name_furigana'];
                $StaffModel['first_name_furigana'] = $Data['first_name_furigana'];
                $StaffModel['staff_type'] = 0;
                $StaffModel['del_flg'] = 0;
                $StaffModel['created_user'] = $IDLoginUser;
                $StaffModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                $StaffModel['updated_user'] = $IDLoginUser;
                $StaffModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");

                $StaffModel -> save();
                return "New Staff is created";
        } 
    }
    function Staff_Detail_Edit(Request $request){
        $Staff_Edit = Validator::make($request->all(),[
            'last_name'=>"required|string|nullable|size:2",
            'first_name'=>"required|string|nullable|size:2",
            'last_name_furigana'=>"required|string|nullable",
            'first_name_furigana'=>"required|string|nullable",
        ],[
            'last_name.required' => "Last name is required",
            'first_name.required' => "First name is required",
            'last_name_furigana.required'=> "Last name furigana is required",
            "first_name_furigana.required" => "first name furigana is required",
        ]);
        if ($Staff_Edit->fails()){
            return $Staff_Edit->errors();
        }
        else{
            $Staff_Edit_Data = $Staff_Edit->getData();
            $Staff = new Staff;

            $IDLoginUser = $request['IDLoginUser'];
            $ID_Staff_Edit = $request['ID_Staff_Edit'];

            $Staff_Find_In_Model = Staff::where("id", $ID_Staff_Edit)->first();
            $Staff_Find_In_Model->update([
                "last_name" => $Staff_Edit_Data['last_name'],
                "first_name" => $Staff_Edit_Data['first_name'],
                "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                'staff_type' => 0,
                'del_flg' => 0,
                'updated_user' => $IDLoginUser,
                'updated_datetime' => now()->setTimezone("Asia/Ho_Chi_Minh"),
            ]);
            return "Staff is edited";
        }
    }
    function HandleSearchStaff(Request $request){
        $Group = [];
        $GroupRightHalf = [];
        $GroupLeftHalf = [];
        $ModelStaff1 = new StaffModel;
        $ModelStaff1 = $ModelStaff1::all();
        foreach ($ModelStaff1 as $key) {
            $last_name = $key['last_name'];
            $first_name = $key['first_name'];
            array_push($Group, $last_name.$first_name);
        }
        foreach ($ModelStaff1 as $RightHalf) {
            $last_name_right_half = $RightHalf['last_name'];
            array_push($GroupRightHalf, $last_name_right_half);
        }
        foreach ($ModelStaff1 as $LeftHalf) {
            $first_name_left_half = $LeftHalf['first_name'];
            array_push($GroupLeftHalf, $first_name_left_half);
        }
        $Validator = Validator::make($request->all(), [
            "full_name"=>"required|string",
            "office"=>"required|string",
        ],[
            "full_name.required"=>"full name is required",
            "office.required"=> "office is required",
        ]);
        if ($Validator->fails()){
            return $Validator->errors();
        }
        else{
            $DataFullName = $Validator->getData();
            $StaffModel = new StaffModel;
                foreach ($Group as $item) {
                    if ($DataFullName['full_name'] == $item){
                        if (substr($item, 0, 2) !== null && substr($item, 2) !== null){
                            $Last_name = $StaffModel::where("last_name", substr($item, 0, 6))->get();
                            $First_name = $StaffModel::where("first_name", substr($item, 6))->get();
                            
                            foreach ($Last_name as $itemLastName) {
                                foreach ($First_name as $itemFirstName) {
                                    if ($itemLastName['last_name'] == $itemFirstName['last_name']){
                                        if($itemLastName['first_name'] == $itemFirstName['first_name']){
                                            return $itemLastName;
                                        }
                                }
                            }
                        }
                        if (substr($item, 0, 2) !== null && substr($item, 2) !== null){
                            $Last_name = $StaffModel::where("last_name", substr($item, 0, 2))->get();
                            $First_name = $StaffModel::where("first_name", substr($item, 2))->get();
                            
                            foreach ($Last_name as $itemLastName) {
                                foreach ($First_name as $itemFirstName) {
                                    if ($itemLastName['last_name'] == $itemFirstName['last_name']){
                                        if($itemLastName['first_name'] == $itemFirstName['first_name']){
                                            return $itemLastName;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    else{
                        return "Nothing in here";
                    }
                }
                foreach ($GroupRightHalf as $RightHalf) {
                    if($DataFullName['full_name'] == $RightHalf){
                        $Get = $StaffModel::where("last_name", $DataFullName['full_name'])->get();
                        return $Get;
                    }
                }
                foreach ($GroupLeftHalf as $LeftHalf) {
                    if($DataFullName['full_name'] == $LeftHalf){
                        $GET = $StaffModel::where("first_name", $DataFullName['full_name'])->get();
                        return $GET;
                    }
                }
            }
        }
    }
}
