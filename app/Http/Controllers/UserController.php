<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Model\User;
use App\Model\VCP;
use App\Model\HOAccount;
use App\Model\RoleApprovalAt;
use App\Http\Requests\UserPostRequest;
use App\Http\Requests\RemoveUserPostRequest;

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

            $response["data"] = User::join("ho_account", "ho_account.user_id", "users.id")->whereNull("ho_account.deleted_at")->select(DB::raw("users.name, users.email"))->get();
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
            HOAccount::create([
                "user_id" => $user->id
            ]);

            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }

    public function delete(RemoveUserPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $user = User::where("email", $request->input("email"))->first();
            HOAccount::where("user_id", $user->id)->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::debug($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }
}
