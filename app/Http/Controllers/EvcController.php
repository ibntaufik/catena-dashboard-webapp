<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Province;
use App\Model\Evc;
use App\Helpers\CommonHelper;
use App\Http\Requests\EvcPostRequest;
use App\Http\Requests\RemoveEvcPostRequest;

class EvcController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));
        return view("master-data.evc.index", compact("province"));
    }

    public function save(EvcPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        $input = $request->except(["_token"]);

        try{

            if(Evc::findByCode($input["code"])){
                $response["message"] = "Evc with code ".$input["code"]." already registered.";
                return response()->json($response);
            }

            $user = Evc::create($input);

            Cache::forget("account.list_combo.evc");
            
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCP ".$input["vch_code"];
        }

        return response()->json($response);
    }

    public function delete(RemoveEvcPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            Evc::where("code", $request->input("code"))->delete();

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
            $response["data"] = Evc::join("sub_districts", "sub_districts.id", "t_evc.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("t_evc.code, t_evc.address, t_evc.latitude, t_evc.longitude, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province"))->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }
}
