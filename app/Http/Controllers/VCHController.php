<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Province;
use App\Model\Evc;
use App\Model\VCH;
use App\Helpers\CommonHelper;
use App\Http\Requests\VCHPostRequest;
use App\Http\Requests\RemoveVchPostRequest;

class VCHController extends Controller
{
    
    public function __construct(){
        
    }

    public function index(Request $request){
        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));

        $evc = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(Evc::listCombo(), true));
        return view("master-data.vch.index", compact("province", "evc"));
    }

    public function save(VCHPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        $input = $request->except(["_token"]);

        try{
            if(VCH::findByCode($input["code"])){
                $response["message"] = "VCH with code ".$input["code"]." already registered.";
                return response()->json($response);
            }

            $evc = Evc::findByCode($input["evc_code"]);
            if(empty($evc)){
                $response["message"] = "EVC with code ".$input["code"]." not registered in system.";
                return response()->json($response);
            } else if(!empty($evc->deleted_at)){
                $response["message"] = "EVC with code ".$input["code"]." have been deleted from system.";
                return response()->json($response);
            }
            
            $input["evc_id"] = $evc->id;
            unset($input["evc_code"]);
            $user = VCH::create($input);

            CommonHelper::forgetWildcard("*vch*");

            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCH ".$input["code"];
        }

        return response()->json($response);
    }

    public function delete(RemoveVchPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            VCH::where("code", $request->input("vch_code"))->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
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
            $response["data"] = Vch::join("t_evc", "t_evc.id", "t_vch.evc_id")->join("sub_districts", "sub_districts.id", "t_vch.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("t_evc.code AS evc_code, t_vch.code, t_vch.address, t_vch.latitude, t_vch.longitude, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province"))->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }
}
