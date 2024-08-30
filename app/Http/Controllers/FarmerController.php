<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CommonHelper;
use App\Http\Requests\FarmerPostRequest;
use App\Http\Requests\RemoveFarmerPostRequest;
use App\Model\Location;
use App\Model\Province;
use App\Model\Subdistrict;
use App\Model\Farmer;
use App\Model\User;

class FarmerController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $province = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Province::listByName(""));

        return view("account.farmer", compact("province"));
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
            $response["data"] = Cache::remember("data.list.farmer", config("constant.ttl"), function(){
                return Farmer::join("sub_districts", "sub_districts.id", "account_farmer.sub_district_id")
                ->leftJoin("users", "account_farmer.user_id", "users.id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->select(DB::raw("account_farmer.code AS farmer_code, users.name, users.email, account_farmer.address, account_farmer.latitude, account_farmer.longitude, users.phone, account_farmer.id_number, sub_districts.code AS sub_district_code, CONCAT(sub_districts.name, ' <br> ', districts.name, ' <br> ', cities.name, ' <br> ', provinces.name) AS location"))->orderBy("account_farmer.created_at", "DESC")->get();
            });
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function list(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "count"     => 0,
            "data"      => []
        ];

        try{

            $page = $request->input("start");
            $limit = $request->input("limit");

            $response["code"] = 200;
            $response["message"] = "Success";
            $response["count"] = Cache::remember("data.list.farmer.count", 120, function(){
                return Farmer::join("sub_districts", "sub_districts.id", "account_farmer.sub_district_id")
                ->leftJoin("users", "account_farmer.user_id", "users.id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->count();
            });

            $response["data"] = Cache::remember("data.list.farmer.page_$page.limit_$limit", 120, function() use($page, $limit){
                return Farmer::join("sub_districts", "sub_districts.id", "account_farmer.sub_district_id")
                ->leftJoin("users", "account_farmer.user_id", "users.id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->when(!empty($page) && !empty($limit), function($builder) use($page, $limit){
                    return $builder->offset($page)->limit($limit);
                })
                ->select(DB::raw("account_farmer.code AS farmer_code, users.name, users.email, account_farmer.address, account_farmer.latitude, account_farmer.longitude, users.phone, account_farmer.id_number, sub_districts.code AS sub_district_code, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province"))->orderBy("account_farmer.created_at", "DESC")
                ->get();
            });
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(FarmerPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except(["_token"]);
        $file = $request->input('file');

        if(User::isEmailExist($input["email"])){
            $response["message"] = "Email already registered, please use other email";
            return response()->json($response);
        }

        if(!empty($input["phone"]) && User::isPhoneExist($input["phone"])){
            $response["message"] = "Phone ".$input["phone"]." already registered, please use other number";
            return response()->json($response);
        }

        if(!empty($input["id_number"])  && Farmer::isIdNumberExist($input["id_number"])){
            $response["message"] = "ID number ".$input["id_number"]." already registered.";
            return response()->json($response);
        }

        try{
            $data = explode(',', $file);
            $fileType = explode(';', $data[0]);
            $isImage = explode(':', $fileType[0]);
            if(strpos($isImage['1'], 'image') === false){
                $output['response']['message'] = 'ID photo must be an image';
                return response()->json($output);
            }
            
            $decode = base64_decode($data[1]);
            if (!$decode){
                $output['response']['message'] = 'ID photo is not valid';
                return response()->json($output);
            }
        } catch (\Exception $e){
            $response['response']['message'] = 'ID photo is not valid';
            return response()->json($response);
        }

        try{
            $prefix = Subdistrict::where("sub_districts.id", $input["sub_district_id"])
                ->select(DB::raw("sub_districts.code"))
                ->first();
            
            $farmer = Farmer::withTrashed()->where("sub_district_id", $input["sub_district_id"])
                ->orderBy("code", "DESC")->select("code")->first();

            if(empty($farmer)){
                $input["code"] = $prefix->code.str_pad(1, 5, "0", STR_PAD_LEFT);
            } else {
                $input["code"] = $prefix->code.str_pad((substr($farmer->code, 12) + 1), 5, "0", STR_PAD_LEFT);
            }

            if (empty($input["email"])) {
                $count = User::count();
                $input["email"] = "farmer$count@gmail.com";
            }

            $dataUser = [
                "email" => $input["email"],
                "password" => Hash::make($input["password"]),
                "name" => $input["name"],
                "phone" => $input["phone"],
                "created_by" => "System Administrator"
            ];

            $user = User::create($dataUser);

            $dataFarmer = [
                "user_id"           => $user->id,
                "code"              => $input["code"],
                "sub_district_id"   => $input["sub_district_id"],
                "id_number"         => $input["id_number"],
                "latitude"          => $input["latitude"],
                "longitude"         => $input["longitude"],
                "address"           => $input["address"],
                "created_by"        => "System Administrator"
            ];
            
            if(!empty($file)){
                Storage::disk("farmerId")->put($input["id_number"].$input["file_type"], file_get_contents($file));
            }

            Farmer::create($dataFarmer);

            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function delete(RemoveFarmerPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            Farmer::where("id_number", $request->input("id_number"))->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
