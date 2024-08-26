<?php

namespace App\Http\Controllers;

use App\Http\Requests\VCPPostRequest;
use App\Http\Requests\RemoveVCPPostRequest;
use App\Model\Province;
use App\Model\VCH;
use App\Model\VCP;
use App\Model\User;
use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VCPController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));

        $vch = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(VCH::listCombo(), true));
        return view("master-data.vcp.index", compact("province", "vch"));
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
            $response["data"] = VCP::join("t_vch", "t_vch.id", "t_vcp.vch_id")
            ->join("t_evc", "t_evc.id", "t_vch.evc_id")
            ->join("sub_districts", "sub_districts.id", "t_vcp.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("t_evc.code AS evc_code, t_vch.code AS vch_code, t_vcp.code AS code, t_vcp.address, t_vcp.latitude, t_vcp.longitude, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province"))->orderBy("t_vcp.code", "ASC")->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
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
            if(VCP::findByCode($input["code"])){
                $response["message"] = "VCP with code ".$input["code"]." already registered.";
                return response()->json($response);
            }

            $vch = VCH::findByCode($input["vch_code"]);
            if(empty($vch)){
                $response["message"] = "VCH with code ".$input["code"]." not registered in system.";
                return response()->json($response);
            } else if(!empty($vch->deleted_at)){
                $response["message"] = "VCH with code ".$input["code"]." have been deleted from system.";
                return response()->json($response);
            }
            $input["vch_id"] = $vch->id;
            unset($input["vch_code"]);
            $user = VCP::create($input);

            CommonHelper::forgetCache("*vcp*");
            
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
            VCP::where("ode", $request->input("vcp_code"))->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
