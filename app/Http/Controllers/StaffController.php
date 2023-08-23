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
        $ID_Login = $SelectedStaff['IDLoginUser']; //need IDlogin from user entered
        $SelectedStaff_Condition = $SelectedStaff['Condition']; // when click to verify, send true!

        $Staff = new Staff;
        $Staff = $Staff::where("id", $SelectedStaffID)->first();

        $User = User::where("id", $ID_Login)->first();
        Auth::login($User);

        if(isset($ID_Login)){
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

        if (isset($IDLoginUser)){
            $User = User::where("id", $IDLoginUser)->first();
            Auth::login($User);
            $StaffTypeArray = ["社員", "パートナー"];

            $Staff_Create = Validator::make($request->all(), [
                'last_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'last_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'staff_type'=>'required|string'
            ], [
                'last_name.required' => "Last name is required",
                "last_name.regex"=> "Last name is Hiragana, Katakana or kanji",
                'first_name.required' => "First name is required",
                "first_name.regex"=>"First name is Hiragana, Katakana or kanji",
                'last_name_furigana.required'=> "Last name furigana is required",
                "last_name_furigana.regex"=>"Last name furigana is Hiragana, Katakana or kanji",
                "first_name_furigana.required" => "first name furigana is required",
                "first_name_furigana.regex"=>"first name furigana is Hiragana, Katakana or kanji",
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

        if (isset($IDLoginUser)){
            $User = User::where("id", $IDLoginUser)->first();
            Auth::login($User);
            $Staff_Edit = Validator::make($request->all(),[
                'last_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'last_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
                'first_name_furigana'=>'required|regex:/^[\p{Hiragana}\p{Katakana}\p{Han}]{0,255}$/u',
            ],[
                'last_name.required' => "Last name is required",
                "last_name.regex"=> "Last name is Hiragana, Katakana or kanji",
                'first_name.required' => "First name is required",
                "first_name.regex"=>"First name is Hiragana, Katakana or kanji",
                'last_name_furigana.required'=> "Last name furigana is required",
                "last_name_furigana.regex"=>"Last name furigana is Hiragana, Katakana or kanji",
                "first_name_furigana.required" => "first name furigana is required",
                "first_name_furigana.regex"=>"first name furigana is Hiragana, Katakana or kanji",
            ]);
            if ($Staff_Edit->fails()){
                return $Staff_Edit->errors();
            }
            else{
                $Staff_Edit_Data = $Staff_Edit->getData();
                $Staff = new Staff;
                $ID_Staff_Edit = $request['ID_Staff_Edit'];
    
                $Staff_Find_In_Model = Staff::where("id", $ID_Staff_Edit)->first();
                $Staff_Find_In_Model->update([
                    "last_name" => $Staff_Edit_Data['last_name'],
                    "first_name" => $Staff_Edit_Data['first_name'],
                    "last_name_furigana" => $Staff_Edit_Data['last_name_furigana'],
                    "first_name_furigana" => $Staff_Edit_Data['first_name_furigana'],
                    'staff_type' => 0,
                    'del_flg' => 0,
                    'updated_user' => Auth::user()->id,
                    'updated_datetime' => now()->setTimezone("Asia/Ho_Chi_Minh"),
                ]);
                return "Staff is edited";
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
        ], [
            "name.regex"=>"Name is kanji, Hiragana or Katakana"
        ]);
        if ($Check->fails()){
            return response()->json([
                "message"=>"Name is kanji, Hiragana or Katakana"
            ]);
        }
        else{
            $IDLoginUser = $request->IDLoginUser;

        $Del_flg = DB::table("m_staffs_data")->where("del_flg", 0)->exists();

        if($Del_flg){
                if(isset($IDLoginUser)){
                    $User = User::where("id", $IDLoginUser)->first();
                    Auth::login($User);

                    if ($request->name == null && $request->staff_type !== null){
                        $FilterQueryNameNull = DB::table("m_staffs_data")->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
                        return $FilterQueryNameNull;
                    }
                    if ($request->name !== null && $request->staff_type !== null){
                        if ($request->name == substr($request->name, 0)){
                            $queryLastName = DB::table("m_staffs_data")->where("last_name", $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
                            $queryFirstName = DB::table("m_staffs_data")->where("first_name", $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)->get();
                            if (count($queryLastName)>0){
                                return $queryLastName;
                            }
                            if (count($queryFirstName)>0){
                                return $queryFirstName;
                            }
                        }
                        if ($request->name == substr($request->name, 0)){
                            $Result = DB::table("m_staffs_data")->where(DB::raw("CONCAT(last_name, first_name)"), $request->name)->where("del_flg", 0)->get();
                            return $Result;
                        }
                        else{
                            $filterQuery = DB::table("m_staffs_data")
                            ->where(DB::raw("CONCAT(last_name,first_name)"), $request->name)->where("staff_type", $request->staff_type)->where("del_flg", 0)
                            ->get();
                            return $filterQuery;
                        }
                    }
                    if ($request->name !== null && $request->staff_type == null){
                        if ($request->name == substr($request->name, 0)){
                            $queryLastName = DB::table("m_staffs_data")->where("last_name", $request->name)->where("del_flg", 0)->get();
                            $queryFirstName = DB::table("m_staffs_data")->where("first_name", $request->name)->where("del_flg", 0)->get();
                            if (count($queryLastName)>0){
                                return $queryLastName;
                            }
                            if (count($queryFirstName)>0){
                                return $queryFirstName;
                            }
                        }
                        else{
                            $FilterQueryStaffTypeNull = DB::table("m_staffs_data")->where(DB::raw("CONCAT(last_name, first_name)"), $request->name)->where("del_flg", 0)->get();
                            return $FilterQueryStaffTypeNull;
                        }
                    }
                    if ($request->name == null && $request->staff_type == null){
                        $FillterQueryNull = DB::table("m_staffs_data")->where("del_flg", 0)->get();
                        return $FillterQueryNull;
                    }
                }
                else{
                    return response()->json([
                        "message"=>"You haven't login yet"
                    ], 404);
                }
        }
        else{
            return $Del_flg;
        }
        }

        
        // $Group = [];
        // $GroupRightHalf = [];
        // $GroupLeftHalf = [];
        // $GroupStaffType = [];
        // $ModelStaff1 = new StaffModel;
        // $ModelStaff1 = $ModelStaff1::all();
        // foreach ($ModelStaff1 as $key) {
        //     $last_name = $key['last_name'];
        //     $first_name = $key['first_name'];
        //     array_push($Group, $last_name.$first_name);
        // }
        // foreach ($ModelStaff1 as $RightHalf) {
        //     $last_name_right_half = $RightHalf['last_name'];
        //     array_push($GroupRightHalf, $last_name_right_half);
        // }
        // foreach ($ModelStaff1 as $LeftHalf) {
        //     $first_name_left_half = $LeftHalf['first_name'];
        //     array_push($GroupLeftHalf, $first_name_left_half);
        // }
        // foreach ($ModelStaff1 as $StaffType) {
        //     $Staff_Type = $StaffType['staff_type'];
        //     array_push($GroupStaffType, $Staff_Type);
        // }
        // $Validator = Validator::make($request->all(), [
        //     "stafftype"=>"nullable",
        //     "full_name"=>"nullable",
        // ],[
        // ]);
        // if ($Validator->fails()){
        //     return $Validator->errors();
        // }
        // else{
        //     $DataFullName = $Validator->getData();
        //     $StaffModel = new StaffModel;
        //         foreach ($Group as $item) {
        //             if ($DataFullName['full_name'] == $item){
        //                 if (substr($item, 0, 2) !== null && substr($item, 2) !== null){
        //                     $Last_name = $StaffModel::where("last_name", substr($item, 0, 6))->get();
        //                     $First_name = $StaffModel::where("first_name", substr($item, 6))->get();
                            
        //                     foreach ($Last_name as $itemLastName) {
        //                         foreach ($First_name as $itemFirstName) {
        //                             if ($itemLastName['last_name'] == $itemFirstName['last_name']){
        //                                 if($itemLastName['first_name'] == $itemFirstName['first_name']){
        //                                     if ($DataFullName['stafftype'] !== null){
        //                                         if($itemLastName['staff_type'] == $DataFullName['stafftype']){
        //                                             return $itemLastName;
        //                                         }
        //                                     }
        //                                     if($DataFullName['stafftype'] == null){
        //                                         return $itemLastName;
        //                                     }
        //                                 }
        //                         }
        //                     }
        //                 }
        //                 if (substr($item, 0, 2) !== null && substr($item, 2) !== null){
        //                     $Last_name = $StaffModel::where("last_name", substr($item, 0, 2))->get();
        //                     $First_name = $StaffModel::where("first_name", substr($item, 2))->get();
                            
        //                     foreach ($Last_name as $itemLastName) {
        //                         foreach ($First_name as $itemFirstName) {
        //                             if ($itemLastName['last_name'] == $itemFirstName['last_name']){
        //                                 if($itemLastName['first_name'] == $itemFirstName['first_name']){
        //                                     if ($DataFullName['stafftype'] !== null){
        //                                         if($itemLastName['staff_type'] == $DataFullName['stafftype']){
        //                                             return $itemLastName;
        //                                         }
        //                                     }
        //                                     if($DataFullName['stafftype'] == null){
        //                                         return $itemLastName;
        //                                     }
        //                                 }
        //                             }
        //                         }
        //                     }
        //                 }
        //             }
        //         }
        //         foreach ($GroupRightHalf as $RightHalf) {
        //             if($DataFullName['full_name'] == $RightHalf){
        //                 $Get = $StaffModel::where("last_name", $DataFullName['full_name'])->get();
        //                 return $Get;
        //             }
        //         }
        //         foreach ($GroupLeftHalf as $LeftHalf) {
        //             if($DataFullName['full_name'] == $LeftHalf){
        //                 $GET = $StaffModel::where("first_name", $DataFullName['full_name'])->get();
        //                 return $GET;
        //             }
        //         }
        //     }
        // }
    //     $request->validate([
    //         'name' => 'nullable|string|max:255',
    //         'staff_type' => 'nullable|in:0,1',
    //     ]);

    //     $requestParams = $request->only(['name', 'staff_type']);
    //     $staffs = $this->performSearch($requestParams);

    //     return response()->json(['staffs' => $staffs]);                                                                                                                         
    // }
    // private function performSearch($params)
    // {
    //     $query = DB::table('m_staffs_data')
    //         ->select('id', 'last_name', 'first_name', 'last_name_furigana', 'first_name_furigana', 'staff_type')
    //         ->where('del_flg', 0);

    //     if (isset($params['name'])) {
    //         $query->where(function ($query) use ($params) {
    //             $fullNameColumn = DB::raw("CONCAT(last_name, ' ', first_name)");
    //             $query->where($fullNameColumn, 'LIKE', "%" . $params['name'] . "%");
    //         });
    //     }

    //     if (isset($params['staff_type'])) {
    //         $query->where('staff_type', $params['staff_type']);
    //     }

    //     return $query->get();
    // }
    
    }
}
