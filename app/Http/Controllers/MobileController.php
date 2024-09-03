<?php

namespace App\Http\Controllers;

use App\Model\User;
use App\Model\VchAccount;
use App\Model\VcpAccount;
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
                    $user = User::where([
                        "users.email"     => $email,
                    ])
                    ->select(DB::raw("users.id, users.password, users.name"))
                    ->first();
                    
                    if(empty($user)){
                        $response["code"] = 403;
                        $response["data"]["email"] = "Email is not registered.";
                    } else if(!Hash::check($password,$user->password)){
                        $response["data"]["password"] = "Password is incorrect";
                    } else {

                        // ================================================================================
                        //          This section populate VCH and VCP that user listed on system
                        // ================================================================================
                        $vchs = VchAccount::join("accounts", "account_vch.account_id", "accounts.id")
                        ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                        ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                        ->where([
                            "accounts.user_id"     => $user->id,
                        ])
                        ->select(DB::raw("CONCAT(t_evc.code, '-', t_vch.code) AS code"))->get()
                        ->toArray();
                        if(!empty($vchs)){
                            foreach ($vchs as $key => $vch) {
                                $response["data"]["vch"][] = $vch["code"];

                                $vchCode = explode("-", $vch["code"]);
                                $vcps = VcpAccount::join("accounts", "account_vcp.account_id", "accounts.id")
                                ->join("t_vcp", "t_vcp.id", "account_vcp.vcp_id")
                                ->join("t_vch", "t_vch.id", "t_vcp.vch_id")
                                ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                                ->where([
                                    "t_vch.code" => $vchCode[1],
                                    "accounts.user_id" => $user->id,
                                ])
                                ->select(DB::raw("CONCAT(t_evc.code, '-', t_vch.code, '-', t_vcp.code) AS code"))
                                ->groupBy(DB::raw("t_evc.code, t_vch.code, t_vcp.code"))
                                ->get()
                                ->toArray();

                                foreach ($vcps as $vcp) {
                                    $response["data"]["vcp"][$vch["code"]][] = $vcp["code"];
                                }
                            }
                        } else {
                            $response["data"]["vch"] = [];
                            $vcps = VcpAccount::join("accounts", "account_vcp.account_id", "accounts.id")
                            ->join("t_vcp", "t_vcp.id", "account_vcp.vcp_id")
                            ->join("t_vch", "t_vch.id", "t_vcp.vch_id")
                            ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                            ->where([
                                "accounts.user_id" => $user->id,
                            ])
                            ->select(DB::raw("CONCAT(t_evc.code, '-', t_vch.code, '-', t_vcp.code) AS code"))
                            ->groupBy(DB::raw("t_evc.code, t_vch.code, t_vcp.code"))
                            ->get()
                            ->toArray();

                            foreach ($vcps as $vcp) {
                                $response["data"]["vcp"][] = $vcp["code"];
                            }
                        }

                        $response["message"] = "Welcome ".$user->name." to Catena";
                        $response["code"] = 200;

                        $response["data"]["name"] = $user->name;
                        $response["data"]["user_id"] = $user->id;
                        $response["data"]["email"] = $email;
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
