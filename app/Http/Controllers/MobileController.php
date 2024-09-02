<?php

namespace App\Http\Controllers;

use App\Model\VchAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MobileController extends Controller
{
    public function login(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to login.",
            "data"      => [],
        ];

        try {
            $emptyResponse = (object) array();
            $input = $request->except('');
            $paramFailed = array();
            $required = ['email', 'password'];
            foreach ($required as $item) {
                if (!array_key_exists($item, $input)) $paramFailed[] = $item;
            }

            if (!empty($paramFailed)) {
                $message = "Missing Parameter : " . implode(', ', $paramFailed).".";
                $response["message"] = $message; 
                $response["data"] = $emptyResponse;
            } else {
                $email = $request->input("email");
                $password = $request->input("password");
                $pass = true;
                if(empty($email)){
                    $response["data"]["email"] = "Email cannot be empty";
                    $pass = false;
                }

                if(empty($password)){
                    $response["data"]["password"] = "Password cannot be empty";
                    $pass = false;
                }

                if($pass){
                    $user = VchAccount::join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->where([
                        "users.email"     => $email,
                    ])
                    ->select(DB::raw("users.id, users.password, t_vch.code, users.name"))->first();
                    
                    if(empty($user)){
                        $response["code"] = 403;
                        $response["data"]["email"] = "Email is not registered.";
                    } else if(!Hash::check($password,$user->password)){
                        $response["data"]["password"] = "Password is incorrect";
                    } else {
                        $response["message"] = "Welcome ".$user->name." to Catena";
                        $response["code"] = 200;
                        $response["data"]["name"] = $user->name;
                        $response["data"]["user_id"] = $user->id;
                        $response["data"]["email"] = $email;
                        $response["data"]["vch"] = $user->code;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return response()->json($response);
    }
}
