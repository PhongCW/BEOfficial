<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
                    $Password = $User['password'];
                    if ($PasswordLogin !== $Password){
                        return "Password is incorrect";
                    } 
                    else {
                        Auth::login($User);
                        return response()->json(["Successfully", $UserIDLogin]);
                    }
                }
            }
        }
    }
}
