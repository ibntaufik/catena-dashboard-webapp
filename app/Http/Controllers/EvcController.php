<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            $province = Province::findBySubdistrictId($input["sub_district_id"]);

            if(Evc::isExist($input["code"], $province->id)){
                $response["message"] = "Evc with code ".$input["code"]." on province ".$province->name." already registered.";
                return response()->json($response);
            }
            
            $input["created_by"] = Auth::user()->name;
            Evc::create($input);

            CommonHelper::forgetCache("evc");
            
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCP ".$input["code"];
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
            CommonHelper::forgetCache("evc");
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

    public function readAssetPublic(Request $request){

        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except(["_token"]);

        if(array_key_exists("transaction_id", $input)){
            if(empty($input["transaction_id"])){
                $response["message"] = "Transaction ID cannot be empty.";
            } else {
                try{
                    $readAsset = new ReadAsset();
                    $response["data"] = $readAsset->public($input["transaction_id"]);
                    $response["code"] = 200;
                    $response["message"] = "Success";
                } catch(\Exception $e){
                    \Log::error($e->getMessage());
                    \Log::error($e->getTraceAsString());
                }
            }
        } else {
            $response["message"] = "Transaction ID is required.";
        }

        return response()->json($response);
    }

    public function readAssetPrivate(Request $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except(["_token"]);

        if(array_key_exists("transaction_id", $input)){
            if(empty($input["transaction_id"])){
                $response["message"] = "Transaction ID cannot be empty.";
            } else {
                $mspPrivateCollection = "ExportMSPPrivateCollection";
                if(array_key_exists("org_msp", $input) && !empty($input["org_msp"])){
                    $mspPrivateCollection = $input["org_msp"];
                }
                try{
                    $readAsset = new ReadAsset();
                    $response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection);
                    $response["code"] = 200;
                    $response["message"] = "Success";
                } catch(\Exception $e){
                    \Log::error($e->getMessage());
                    \Log::error($e->getTraceAsString());
                }
            }
        } else {
            $response["message"] = "Transaction ID is required.";
        }

        return response()->json($response);
    }
}
