<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Model\Location;
use App\Model\Province;
use App\Model\City;
use App\Model\District;
use App\Model\Subdistrict;
use App\Helpers\CommonHelper;
use App\Http\Requests\LocationPostRequest;
use App\Http\Requests\RemoveLocationPostRequest;

class LocationController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));
        return view("master-data.location", compact("province"));
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
            $response["data"] = Subdistrict::join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("sub_districts.code, sub_districts.name AS sub_district, sub_districts.latitude, sub_districts.longitude, districts.name AS district, cities.name AS city, provinces.name AS province"))->orderby("provinces.name", "ASC")->get();
        } catch(\Exception $e){
            
        }
        
        return response()->json($response);
    }

    public function save(LocationPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $input = $request->except(["_token"]);
            if(array_key_exists("code", $input) && empty($input["code"])){
                $subdistrict = Subdistrict::withTrashed()->where("district_id", $input["district_id"])
                ->orderBy("code", "DESC")->select("code")->first();

                $prefix = Province::join("cities", "cities.province_id", "provinces.id")
                ->join("districts", "districts.city_id", "cities.id")
                ->where("districts.id", $input["district_id"])->select(DB::raw("CONCAT(provinces.code, cities.code, districts.code) AS code"))->first();

                if(empty($subdistrict)){
                    $input["code"] = $prefix->code.str_pad(1, 3, "0", STR_PAD_LEFT);
                } else {
                    $input["code"] = $prefix->code.str_pad((substr($subdistrict->code, 9) + 1), 3, "0", STR_PAD_LEFT);
                }
            }

            $input["created_by"] = Auth::user()->name;
            Subdistrict::create($input);

            CommonHelper::forgetCache("coverage");

            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }

    public function delete(RemoveLocationPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            Subdistrict::where("code", $request->input("location_id"))->delete();
            CommonHelper::forgetCache("coverage");
            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
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

            $result = Location::select("code", "sub_district")->get();
            
            $response["code"] = "200";
            $response["message"] = "Done";
            $response["data"] = $result;
        } catch(\Exception $e){
            $response["message"] = "Failed to complete request: error when trying to get user list from db";
        }

        return response()->json($response);
    }

    public function listComboProvince(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{
            $name = "";
            if($request->has("name") && ($request->input("name") != null) && ($request->input("name") != "")){
                $name = $request->input("name");
            }
            $province = Province::listByName($name);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
    }

    public function listComboCity(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{
            $provinceId = "";
            if($request->has("province_id") && ($request->input("province_id") != null) && ($request->input("province_id") != "")){
                $provinceId = $request->input("province_id");
            }
            $response["code"] = 200;
            $response["message"] = "Request complete.";
            $response["data"] = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], City::listByProvinceId($provinceId));
                
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return response()->json($response);     
    }

    public function listComboDistrict(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{
            $cityId = "";
            if($request->has("city_id") && ($request->input("city_id") != null) && ($request->input("city_id") != "")){
                $cityId = $request->input("city_id");
            }
            $response["code"] = 200;
            $response["message"] = "Request complete.";
            $response["data"] = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], District::listByCityId($cityId));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }

    public function listComboSubDistrict(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{
            $districtId = "";
            if($request->has("district_id") && ($request->input("district_id") != null) && ($request->input("district_id") != "")){
                $districtId = $request->input("district_id");
            }
            $response["code"] = 200;
            $response["message"] = "Request complete.";
            $response["data"] = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], Subdistrict::listByDistrictId($districtId));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }

    public function coverage(){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        try{
            $response["data"]["province"] = Cache::remember("location.api.coverage.province", config("constant.ttl"), function(){
                return Province::select(DB::raw("id, name, code"))->get()->toArray();
            });
            $response["data"]["city"] = Cache::remember("location.api.coverage.city", config("constant.ttl"), function(){
                return City::select(DB::raw("id, name, code, province_id"))->get()->toArray();
            });
            $response["data"]["district"] = Cache::remember("location.api.coverage.district", config("constant.ttl"), function(){
                return District::select(DB::raw("id, name, code, city_id"))->get()->toArray();
            });
            $response["data"]["sub_district"] = Cache::remember("location.api.coverage.sub_district", config("constant.ttl"), function(){
                return Subdistrict::select(DB::raw("id, name, code, district_id, latitude, longitude"))->get()->toArray();
            });

            $response["message"] = "done";
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return response()->json($response);
    }
}
