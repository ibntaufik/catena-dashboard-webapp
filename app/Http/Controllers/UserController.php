<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Model\User;
use App\Model\VCP;
use App\Model\RoleApprovalAt;
use App\Http\Requests\UserPostRequest;

class UserController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $candidate = [
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ];

        $result = VCP::select(DB::raw("vcp_code"))->get()->toArray();
        $result = collect($result)->map(function ($item) {
            return ["id" => $item['vcp_code'], "text" => $item['vcp_code']];
        });
        $candidate = array_merge($candidate, json_decode($result, true));

        return view("master-data.user", compact("candidate"));
    }

    public function datatables(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        try{
            $response["code"] = 200;
            $response["message"] = "Success";
            $response["data"] = User::leftJoin("role_approval_at", "role_approval_at.user_id", "users.id")->leftJoin("vcp_account", "vcp_account.id", "role_approval_at.vcp_account_id")->select(DB::raw("users.name, users.email, role_approval_at.role_at, vcp_account.vcp_code"))->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function combo(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{

            $result = User::select("id", "name")->get();
            
            $response["code"] = "200";
            $response["message"] = "Done";
            $response["data"] = $result;
        } catch(\Exception $e){
            $response["message"] = "Failed to complete request: error when trying to get user list from db";
        }

        return response()->json($response);
    }

    public function save(UserPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $input = $request->except(["_token"]);
            $dataUser = [
                "email" => $input["email"],
                "password" => Hash::make($input["password"]),
                "name" => $input["name"],
                "created_by" => "System Administrator"
            ];

            $user = User::create($dataUser);

            $dataRoleApprovalAt = [
                "user_id"   => $user->id,
                "role_at"   => $input["role_at"],
                "created_by" => "System Administrator"
            ];
            if(!empty($input["code"])){
                $vcpAccount = VCP::findByCode($input["code"]);
                if(!empty($vcpAccount)){
                    $dataRoleApprovalAt["vcp_account_id"] = $vcpAccount->id;
                }
            }

            RoleApprovalAt::create($dataRoleApprovalAt);
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
