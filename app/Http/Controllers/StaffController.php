<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffModel;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\ResponseInterface;
use SebastianBergmann\LinesOfCode\Counter;

class StaffController extends Controller
{
    function Show_Staff_Screen(Request $request){
        $Staff = new Staff;
        $Staff = $Staff::where("del_flg", 0)->orderBy('created_datetime', 'DESC')->get();
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
        $ID_Login = $SelectedStaff['IDLoginUser']; //need IDlogin from user entered
        $SelectedStaff_Condition = $SelectedStaff['Condition']; // when click to verify, send true!

        // $IDLoginUser = session("IDLoginUser");
        $Staff = new Staff;
        $Staff = $Staff::where("id", $SelectedStaffID)->first();

        $User = User::where("id", $ID_Login)->first();
        Auth::login($User);

        if(isset($User)){
            if ($SelectedStaff_Condition == true){
                $Staff->update([
                    'del_flg' => 1,
                    'updated_user'=> Auth::user()->id,
                    'updated_datetime'=> now()->setTimezone("Asia/Ho_Chi_Minh"),
                ]);
                return "Deleted Successfully";
            }
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet",
            ], 404);
        }
    }
    function Staff_Create(Request $request){

        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();
        if (isset($User)){
            Auth::login($User);
            $StaffTypeArray = [0, 1];

            $Staff_Create = Validator::make($request->all(), [
                'last_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'last_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'staff_type'=>'required|string'
            ], [
                'last_name.required' => "Last name is required",
                "last_name.regex"=> "Only input 2 byte character!",
                'first_name.required' => "First name is required",
                "first_name.regex"=>"Only input 2 byte character!",
                'last_name_furigana.required'=> "Last name furigana is required",
                "last_name_furigana.regex"=>"Only input 2 byte character!",
                "first_name_furigana.required" => "first name furigana is required",
                "first_name_furigana.regex"=>"Only input 2 byte character!",
                'staff_type.required'=>'staff type is required'
            ]);
            if ($Staff_Create->fails()) {
                return $Staff_Create->errors();
            }
            else{
                $Data = $Staff_Create->getData();

                $StaffModel = new StaffModel;

                
                $StaffModel['last_name'] = $Data['last_name'];
                $StaffModel['first_name'] = $Data['first_name'];
                $StaffModel['last_name_furigana'] = $Data['last_name_furigana'];
                $StaffModel['first_name_furigana'] = $Data['first_name_furigana'];

                if($Data['staff_type'] == $StaffTypeArray[0]){
                    $StaffModel['staff_type'] = 0;
                }
                else{
                    $StaffModel['staff_type'] = 1;
                }
                $StaffModel['del_flg'] = 0;
                $StaffModel['created_user'] = Auth::user()->id;
                $StaffModel['created_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");
                $StaffModel['updated_user'] = Auth::user()->id;
                $StaffModel['updated_datetime'] = now()->setTimezone("Asia/Ho_Chi_Minh");

                $StaffModel -> save();
                return "New Staff is created";
            } 
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ], 404);
        }

    }
    function Staff_Detail_Edit(Request $request){
        $IDLoginUser = $request->IDLoginUser;
        // $IDLoginUser = session("IDLoginUser");
        $User = User::where("id", $IDLoginUser)->first();
        if (isset($User)){
            Auth::login($User);
            $Staff_Edit = Validator::make($request->all(),[
                'last_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'last_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
            ],[
                'last_name.required' => "Last name is required",
                "last_name.regex"=> "Only input 2 bytes character!",
                'first_name.required' => "First name is required",
                "first_name.regex"=>"Only input 2 bytes character!",
                'last_name_furigana.required'=> "Last name furigana is required",
                "last_name_furigana.regex"=>"Only input 2 bytes character!",
                "first_name_furigana.required" => "first name furigana is required",
                "first_name_furigana.regex"=>"Only input 2 bytes character!",
            ]);
            if ($Staff_Edit->fails()){
                return $Staff_Edit->errors();
            }
            else{
                $Staff_Edit_Data = $Staff_Edit->getData();
                $Staff = new Staff;
                $ID_Staff_Edit = $request['ID_Staff_Edit'];
                $StaffTypeArray = [0, 1];

                $staff_type = $Staff_Edit_Data['staff_type'] == $StaffTypeArray[0] ? 0 : 1;

                $Staff_Find_In_Model = Staff::where("id", $ID_Staff_Edit)->first();
                $Staff_Find_In_Model->update([
                    "last_name" => $Staff_Edit_Data['last_name'],
                    "first_name" => $Staff_Edit_Data['first_name'],
                    "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                    "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                    'staff_type' => $staff_type,
                    'del_flg' => 0,
                    'updated_user' => Auth::user()->id,
                    'updated_datetime' => now()->setTimezone("Asia/Ho_Chi_Minh"),
                ]);
                return response()->json([
                    "message"=>"Staff is edited"
                ]);
            }
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ], 404);
        }
    }
    function HandleSearchStaff(Request $request){

        $Check = Validator::make($request->all(), [
            "name"=> 'nullable|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
            "staff_type"=>"nullable|numeric"
        ]);
        $IDLoginUser = $request->IDLoginUser;
        $User = User::where("id", $IDLoginUser)->first();
        if (isset($User)){
            Auth::login($User);
            $name = $request->input("name");
            $staff_type = $request->input("staff_type");

            $query = DB::table("m_staffs_data")->where("del_flg", 0);

            if ($name !== null){
                $query->where(DB::raw("CONCAT(last_name, first_name)"), "LIKE", "%$name%");
            }
            if ($staff_type !== null){
                $query->where("staff_type", "LIKE", "%$staff_type%");
            }
            return $query->orderBy("created_datetime", "DESC")->get();
        }
        else{
            return response()->json([
                "message"=>"You haven't login yet"
            ], 404);
        }
        // $IDLoginUser = session("IDLoginUser");

    //     $Del_flg = DB::table("m_staffs_data")->where("del_flg", 0)->exists();

    //     if($Del_flg){
    //             $User = User::where("id", $IDLoginUser)->first();
    //             if(isset($User)){
    //                 Auth::login($User);

    //                 if ($request->name == null && $request->staff_type !== null){
    //                     $FilterQueryNameNull = DB::table("m_staffs_data")->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
    //                     return $FilterQueryNameNull;
    //                 }
    //                 if ($request->name !== null && $request->staff_type !== null){
    //                     if ($request->name == substr($request->name, 0)){
    //                         $queryLastName = DB::table("m_staffs_data")->where("last_name", $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
    //                         $queryFirstName = DB::table("m_staffs_data")->where("first_name", $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
    //                         $queryFullNamee = DB::table("m_staffs_data")->where(DB::raw("CONCAT(last_name, first_name)"), $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
    //                         if (count($queryLastName)>0){
    //                             return $queryLastName;
    //                         }
    //                         if (count($queryFirstName)>0){
    //                             return $queryFirstName;
    //                         }
    //                         if (count($queryFullNamee)>0){
    //                             return $queryFullNamee;
    //                         }
    //                         else{
    //                             return [];
    //                         }
    //                     }
    //                 }
    //                 if ($request->name !== null && $request->staff_type == null){
    //                     if ($request->name == substr($request->name, 0)){
    //                         $queryLastName = DB::table("m_staffs_data")->where("last_name", $request->name)->where("del_flg", 0)->get();
    //                         $queryFirstName = DB::table("m_staffs_data")->where("first_name", $request->name)->where("del_flg", 0)->get();
    //                         $queryFullName = DB::table("m_staffs_data")->where(DB::raw("CONCAT(last_name, first_name)"), $request->name)->where("del_flg", 0)->get();
    //                         if (count($queryLastName)>0){
    //                             return $queryLastName;
    //                         }
    //                         if (count($queryFirstName)>0){
    //                             return $queryFirstName;
    //                         }
    //                         if (count($queryFullName)>0){
    //                             return $queryFullName;
    //                         }
    //                         else{
    //                             return [];
    //                         }
    //                     }
    //                     else{
    //                         $FilterQueryStaffTypeNull = DB::table("m_staffs_data")->where(DB::raw("CONCAT(last_name, first_name)"), $request->name)->where("del_flg", 0)->get();
    //                         return $FilterQueryStaffTypeNull;
    //                     }
    //                 }
    //                 if ($request->name == null && $request->staff_type == null){
    //                     $FillterQueryNull = DB::table("m_staffs_data")->where("del_flg", 0)->get();
    //                     return $FillterQueryNull;
    //                 }
    //             }
    //             else{
    //                 return response()->json([
    //                     "message"=>"You haven't login yet"
    //                 ], 404);
    //             }
    //     }
    //     else{
    //         return $Del_flg;
    //     }
    }
}
