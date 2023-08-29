<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function Login(Request $request){

        $UserLogin = $request;

        $UserIDLogin = $UserLogin['userID'];
        $PasswordLogin = $UserLogin['Password'];

        if ($UserIDLogin == null && $PasswordLogin !== null){
            return "UserID is required";
        }
        if ($PasswordLogin == null && $UserIDLogin !== null){
            return "PasswordLogin is required";
        }
        if ($UserIDLogin == null && $PasswordLogin == null){
            return "UserID and PasswordLogin are required";
        }
        if ($UserIDLogin !== null && $PasswordLogin !== null){
            $User = new User;
            $User = $User::where("id", $UserIDLogin)->first();
            if ($User == null){
                return "UserIDLogin is not exist";
            }
            if ($User !== null){
                if ($User['del_flg'] == 1){
                    return "UserID is not available";
                }
                if ($User['del_flg'] == 0){
                    if ($User && hash::check($PasswordLogin, $User->password)){
                        Auth::login($User);
                        session()->put("IDLoginUser", Auth::user()->id);
                        return response()->json([
                            "message"=>"Successfully",
                            "IDLoginUser" => Auth::user()->id
                        ], 200);
                    }
                    else{
                        return "Password is incorrect";
                    }
                }
            }
        }
    }
    // public function login(Request $request)
    // {
    //     $rules = [
    //         'userID' => 'required',
    //         'password' => 'required',
    //     ];

    //     $messages = [
    //         'userID.required' => 'UserID is required',
    //         'password.required' => 'Password is required',
    //     ];

    //     $validator = Validator::make($request->all(), $rules, $messages);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()->first()], 400);
    //     }

    //     $user = User::where('id', $request->userID)->where('del_flg', 0)->first();

    //     if (!$user) {
    //         return response()->json(['error' => 'Invalid credentials or user not available.'], 400);
    //     }

    //     if (!Hash::check($request->password, $user->password)) {
    //         return response()->json(['error' => 'Invalid credentials.'], 400);
    //     }

    //     Auth::login($user);
    //     return response()->json(['message' => 'Successfully logged in', 'userID' => $request->userID]);
    // }
}
