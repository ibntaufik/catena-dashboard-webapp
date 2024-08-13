<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VCPPostRequest;
use App\Http\Requests\RemoveVCPPostRequest;
use App\Model\Province;
use App\Model\VCP;
use App\Model\User;

class VCPController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));

        return view("account.vcp", compact("province"));
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
            $response["data"] = VCP::leftJoin("users", "vcp_account.user_id", "users.id")->join("sub_districts", "sub_districts.id", "vcp_account.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")->select(DB::raw("vcp_account.vcp_code, users.email, CONCAT(sub_districts.code, ' <br> ', sub_districts.name, ' <br> ', districts.name, ' <br> ', cities.name, ' <br> ', provinces.name) AS location, vcp_account.address, vcp_account.latitude, vcp_account.longitude, vcp_account.field_coordinator_id, vcp_account.field_coordinator_name"))->get();
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

        if(User::isEmailExist($input["email"])){
            $response["message"] = "Email already registered, please use other email";
            return response()->json($response);
        }
        
        try{
            $dataUser = [
                "email" => $input["email"],
                "password" => Hash::make($input["password"]),
                "name" => $input["field_coordinator_name"],
                "created_by" => "System Administrator"
            ];

            $user = User::create($dataUser);

            $input["created_by"] = "Admin";
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
