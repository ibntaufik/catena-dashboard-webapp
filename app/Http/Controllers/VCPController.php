<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VCPPostRequest;
use App\Http\Requests\RemoveVCPPostRequest;
use App\Model\Location;
use App\Model\VCP;
use App\Model\User;

class VCPController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $candidate = [
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ];

        $result = Location::select(DB::raw("code, sub_district"))->get()->toArray();
        $result = collect($result)->map(function ($item) {
            return ["id" => $item['code'], "text" => $item['sub_district']];
        });
        $candidate = array_merge($candidate, json_decode($result, true));

        return view("account.vcp", compact("candidate"));
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
            $response["data"] = VCP::leftJoin("location", "location.id", "vcp_account.location_id")->leftJoin("users", "vcp_account.user_id", "users.id")->select(DB::raw("vcp_account.vcp_code, users.email, location.sub_district, vcp_account.address, vcp_account.latitude, vcp_account.longitude, vcp_account.field_coordinator_id, vcp_account.field_coordinator_name"))->get();
        } catch(\Exception $e){
            
        }
        
        return response()->json($response);
    }

    public function save(VCPPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        $input = $request->except(["_token"]);
        try{
            $dataUser = [
                "email" => $input["email"],
                "password" => Hash::make($input["password"]),
                "name" => $input["field_coordinator_name"],
                "created_by" => "System Administrator"
            ];

            $user = User::create($dataUser);

            $input["created_by"] = "Admin";
            $location = Location::findByCode($input["location_code"]);
            $input["location_id"] = $location->id;
            $input["user_id"]  = $user->id;
            unset($input["location_code"]);
            VCP::create($input);
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCP ".$input["vcp_code"];
        }
        
        return response()->json($response);
    }

    public function delete(RemoveVCPPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            VCP::where("vcp_code", $request->input("vcp_code"))->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
