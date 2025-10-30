<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CommonHelper;
use App\Helpers\Fabric\FarmerMSP\ReadAsset as FarmerReadAsset;
use App\Helpers\Fabric\PulperMSP\ReadAsset as PulperReadAsset;
use App\Helpers\Fabric\HullerMSP\ReadAsset as HullerReadAsset;
use App\Helpers\Fabric\ExportMSP\ReadAsset as ExportReadAsset;
use App\Helpers\Fabric\HeadOfficeMSP\ReadAsset as HeadOfficeReadAsset;
use App\Http\Requests\FarmerPostRequest;
use App\Http\Requests\RemoveFarmerPostRequest;
use App\Model\Bank;
use App\Model\BusinessType;
use App\Model\Coffee;
use App\Model\CoffeeVariety;
use App\Model\Farms;
use App\Model\FarmDetail;
use App\Model\LandStatus;
use App\Model\Location;
use App\Model\Province;
use App\Model\ShadeTree;
use App\Model\Subdistrict;
use App\Model\Supplier;
use App\Model\SupplierAsset;
use App\Model\SupplyCategory;
use App\Model\User;
use App\Model\Evc;
use App\Model\VCH;
use App\Model\VCP;

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
            "count"     => 0,
            "data"      => []
        ];

        try{
            $response["code"] = 200;
            $response["message"] = "Success";

            $page = $request->input("start");
            $limit = $request->input("limit");

            $cacheName = "list.farmer";
            $name = $request->input("f_name");
            if($name){
                $cacheName .= ".name_".strtolower(preg_replace('/\s+/', '', $name));

            }

            $emailUser = $request->input("email_user");
            if($emailUser){
                $cacheName .= ".email_user_".strtolower(preg_replace('/\s+/', '', $emailUser));

            }

            $phone = $request->input("phone");
            if($emailUser){
                $cacheName .= ".phone_$phone";

            }

            $idNumber = $request->input("id_number");
            if($idNumber){
                $cacheName .= ".id_numner_$idNumber";

            }

            $latitude = $request->input("latitude");
            if($latitude){
                $cacheName .= ".latitude_$latitude";

            }

            $longitude = $request->input("longitude");
            if($longitude){
                $cacheName .= ".longitude_$longitude";

            }

            $provinceId = $request->input("province_id");
            if($provinceId){
                $cacheName .= ".province_id_$provinceId";

            }

            $cityId = $request->input("city_id");
            if($cityId){
                $cacheName .= ".city_id_$cityId";

            }

            $districtId = $request->input("dictrict_id");
            if($districtId){
                $cacheName .= ".dictrict_id_$districtId";

            }

            $subDistrictId = $request->input("sub_dictrict_id");
            if($subDistrictId){
                $cacheName .= ".sub_dictrict_id_$subDistrictId";

            }

            if($page){
                $cacheName .= ".page|$page";
            }

            $response["count"] = Cache::remember("count.$cacheName", config("constant.ttl"), function() use($name, $emailUser, $phone, $idNumber, $latitude, $longitude, $provinceId, $cityId, $districtId, $subDistrictId){
                return Supplier::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->when($name, function($builder) use($name){
                    return $builder->whereRaw("UPPER(master_supplier.name) LIKE ?", ["%".strtoupper($name)."%"]);
                })
                ->when($emailUser, function($builder) use($emailUser){
                    return $builder->whereRaw("LOWER(master_supplier.email) LIKE ?", ["%".strtolower($emailUser)."%"]);
                })
                ->when($phone, function($builder) use($phone){
                    return $builder->whereRaw("master_supplier.phone LIKE ?", [$phone]);
                })
                ->when($idNumber, function($builder) use($idNumber){
                    return $builder->whereRaw("master_supplier.id_number LIKE ?", [$idNumber]);
                })
                ->when($latitude, function($builder) use($latitude){
                    return $builder->whereRaw("master_supplier.latitude LIKE ?", [$latitude]);
                })
                ->when($longitude, function($builder) use($longitude){
                    return $builder->whereRaw("master_supplier.longitude LIKE ?", [$longitude]);
                })
                ->when($provinceId, function($builder) use($provinceId){
                    return $builder->whereRaw("provinces.id = ?", [$provinceId]);
                })
                ->when($cityId, function($builder) use($cityId){
                    return $builder->whereRaw("cities.id = ?", [$cityId]);
                })
                ->when($districtId, function($builder) use($districtId){
                    return $builder->whereRaw("districts.id = ?", [$districtId]);
                })
                ->when($subDistrictId, function($builder) use($subDistrictId){
                    return $builder->whereRaw("sub_districts.id = ?", [$subDistrictId]);
                })
                ->count();
            });

            $response["data"] = Cache::remember("data.$cacheName", config("constant.ttl"), function() use($name, $emailUser, $phone, $idNumber, $latitude, $longitude, $provinceId, $cityId, $districtId, $subDistrictId, $page, $limit){
                $supplier = Supplier::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->when($name, function($builder) use($name){
                    return $builder->whereRaw("UPPER(master_supplier.name) LIKE ?", ["%".strtoupper($name)."%"]);
                })
                ->when($emailUser, function($builder) use($emailUser){
                    return $builder->whereRaw("LOWER(master_supplier.email) LIKE ?", ["%".strtolower($emailUser)."%"]);
                })
                ->when($phone, function($builder) use($phone){
                    return $builder->whereRaw("master_supplier.phone LIKE ?", [$phone]);
                })
                ->when($idNumber, function($builder) use($idNumber){
                    return $builder->whereRaw("master_supplier.id_number LIKE ?", [$idNumber]);
                })
                ->when($latitude, function($builder) use($latitude){
                    return $builder->whereRaw("master_supplier.latitude LIKE ?", [$latitude]);
                })
                ->when($longitude, function($builder) use($longitude){
                    return $builder->whereRaw("master_supplier.longitude LIKE ?", [$longitude]);
                })
                ->when($provinceId, function($builder) use($provinceId){
                    return $builder->whereRaw("provinces.id = ?", [$provinceId]);
                })
                ->when($cityId, function($builder) use($cityId){
                    return $builder->whereRaw("cities.id = ?", [$cityId]);
                })
                ->when($districtId, function($builder) use($districtId){
                    return $builder->whereRaw("districts.id = ?", [$districtId]);
                })
                ->when($subDistrictId, function($builder) use($subDistrictId){
                    return $builder->whereRaw("sub_districts.id = ?", [$subDistrictId]);
                })
                ->when($page, function($builder) use($page){
                    return $builder->skip($page);
                })
                ->when($limit, function($builder) use($limit){
                    return $builder->take($limit);
                })
                ->select(DB::raw("master_supplier.code AS farmer_code, master_supplier.name, master_supplier.email, master_supplier.address, master_supplier.latitude, master_supplier.longitude, master_supplier.phone, master_supplier.id_number, sub_districts.code AS sub_district_code, CONCAT(sub_districts.name, ' <br> ', districts.name, ' <br> ', cities.name, ' <br> ', provinces.name) AS location"))->orderBy("master_supplier.local_created_at", "DESC")->get();

                foreach($supplier as $row){
                    $row->asset = Supplier::join("supplier_assets", "master_supplier.id", "supplier_assets.supplier_id")
                    ->where([
                        "master_supplier.code" => $row->farmer_code,
                        "supplier_assets.asset_type" => "identity"
                    ])
                    ->select("supplier_assets.name")
                    ->get();
                }
                return $supplier;
            });

            if($response["count"] > 0){
                $response["code"] = 200;
                $response["message"] = "Success";
            } else {
                $response["code"] = 404;
                $response["message"] = "Not found";
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function detail(Request $request)
    {
        $response = [
            "code"    => 400,
            "message" => "Failed to complete request",
            "data"    => []
        ];

        try {
            $response["code"] = 200;
            $response["message"] = "Success";

            $idNumber   = $request->input("id");
            $farmerCode = $request->input("code");

            // Cache key
            $cacheName = "detail.farmer";
            if ($idNumber) {
                $cacheName .= ".id_number|$idNumber";
            }
            if ($farmerCode) {
                $cacheName .= ".farmer_code|$farmerCode";
            }

            // Get Farmer Detail
            $result = Cache::remember($cacheName, config("constant.ttl"), function() use ($idNumber, $farmerCode) {
                return Supplier::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                    ->join("districts", "districts.id", "sub_districts.district_id")
                    ->join("cities", "cities.id", "districts.city_id")
                    ->join("provinces", "provinces.id", "cities.province_id")
                    ->join("t_vch", "t_vch.id", "master_supplier.vch_id")
                    ->join("t_vcp", "t_vcp.id", "master_supplier.vcp_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("master_business_type", "master_business_type.id", "master_supplier.business_type_id")
                    ->join("master_supply_categories", "master_supply_categories.id", "master_supplier.category_id")
                    ->join("master_bank", "master_bank.id", "master_supplier.bank_id")
                    ->when($idNumber, function($builder) use ($idNumber) {
                        return $builder->whereRaw("master_supplier.id_number LIKE ?", [$idNumber]);
                    })
                    ->when($farmerCode, function($builder) use ($farmerCode) {
                        return $builder->whereRaw("master_supplier.code LIKE ?", [$farmerCode]);
                    })
                    ->select(DB::raw("
                        master_supplier.id,
                        master_supplier.code AS farmer_code, 
                        master_supplier.name, 
                        master_supplier.alias, 
                        master_supplier.phone, 
                        master_supplier.email, 
                        master_supplier.address, 
                        master_supplier.latitude, 
                        master_supplier.longitude, 
                        master_supplier.id_number, 
                        master_supplier.business_name, 
                        master_supplier.business_type_id, 
                        master_supplier.authentication_code, 
                        master_supplier.npwp,
                        master_supplier.account_name, 
                        master_supplier.account_number, 
                        master_supplier.verification_status, 
                        master_supplier.local_created_at,
                        master_supplier.vch_id,
                        master_supplier.vcp_id,
                        sub_districts.code AS sub_district_code,
                        sub_districts.name AS sub_district_name, 
                        sub_districts.id AS sub_district_id,
                        districts.name AS district_name, 
                        districts.code AS district_code, 
                        districts.id AS district_id,
                        cities.name AS city_name, 
                        cities.code AS city_code, 
                        cities.id AS city_id,
                        provinces.name AS province_name, 
                        provinces.code AS province_code, 
                        provinces.id AS province_id,
                        t_evc.code AS evc_code,
                        t_evc.id AS evc_id, 
                        t_vch.code AS vch_code, 
                        t_vcp.code AS vcp_code, 
                        master_business_type.code AS business_type_code, 
                        master_business_type.name AS business_type_name, 
                        master_supply_categories.id AS supply_categories_id, 
                        master_supply_categories.code AS category_code,
                        master_supply_categories.name AS category_name,
                        master_bank.id AS bank_id, 
                        master_bank.code AS bank_code, 
                        master_bank.name AS bank_name, 
                        CONCAT(sub_districts.name, ', ', districts.name, ', ', cities.name, ', ', provinces.name) AS location"))
                    ->first();
            });

            // Supplier Asset
            $supplierAsset = Cache::remember("supplier_asset.supplier_code|$farmerCode", config("constant.ttl"), function() use ($result) {
                return SupplierAsset::where("supplier_id", $result->id)
                    ->select("name", "asset_type")
                    ->get();
            });

            $apiUrl     = config('constant.api_url') ?? constant('api_url');
            $assetPaths = config('constant.asset_path') ?? constant('asset_path');

            foreach ($supplierAsset as $asset) {
                $type = $asset->asset_type;
                if (!isset($assetPaths[$type])) continue;
                $assetUrl = "{$apiUrl}/storage/{$assetPaths[$type]}/{$asset->name}";
                $asset->{$type . '_image'} = $assetUrl;
            }

            // Farms
            $farms = Cache::remember("supplier.farms.supplier_code|$farmerCode", config("constant.ttl"), function() use ($result) {
                return Farms::join("master_coffee", "master_coffee.id", "farms.coffee_id")
                    ->join("master_coffee_variety", "master_coffee_variety.id", "farms.coffee_variety_id")
                    ->join("master_shade_tree", "master_shade_tree.id", "farms.shade_tree_id")
                    ->join("master_land_status", "master_land_status.id", "farms.land_status_id")
                    ->where("supplier_id", $result->id)
                    ->select(DB::raw("
                        master_coffee.id AS coffee_id,
                        master_coffee.name AS coffee_name,
                        master_coffee_variety.id AS coffee_variety_id,
                        master_coffee_variety.name AS coffee_variety_name,
                        master_shade_tree.id AS shade_tree_id,
                        master_shade_tree.name AS shade_tree_name,
                        farms.id AS farm_id,
                        farms.address,
                        farms.land_certificate,
                        farms.latitude AS farm_latitude,
                        farms.longitude AS farm_longitude,
                        farms.elevation AS farm_altitude,
                        farms.land_measurement,
                        farms.tree_population,
                        master_land_status.id AS land_status_id,
                        master_land_status.name AS land_status
                    "))
                    ->get();
            });

            // Farm Details (Photos)
            $farmIds = $farms->pluck('farm_id');
            $farmDetails = Cache::remember("supplier.farm.details.supplier_code|$farmerCode", config("constant.ttl"), function() use ($farmIds) {
                return FarmDetail::whereIn("farm_id", $farmIds)
                    ->select("farm_id", "photo")
                    ->get()
                    ->groupBy('farm_id');
            });

            // Attach Photos to Each Farm with Full URL
            $farmPhotoBase = "{$apiUrl}/storage/{$assetPaths['farm']}/";

            $farms->transform(function ($farm) use ($farmDetails, $farmPhotoBase) {

                // Attach photos with URLs
                $photos = $farmDetails[$farm->farm_id] ?? collect();
                $farm->photos = $photos->map(fn($photo) => [
                    'photo' => $photo->photo,
                    'url'   => $farmPhotoBase . $photo->photo,
                ]);

                // Format numeric values for readability
                $formattedLand = isset($farm->land_measurement)
                    ? number_format($farm->land_measurement, 0, ',', '.')
                    : '-';

                $formattedTrees = isset($farm->tree_population)
                    ? number_format($farm->tree_population, 0, ',', '.')
                    : '-';

                $formattedAltitude = isset($farm->altitude)
                    ? number_format($farm->altitude, 0, ',', '.')
                    : '-';

                // Add formatted fields back to the object
                $farm->land_measurement = $formattedLand;
                $farm->tree_population  = $formattedTrees;
                $farm->altitude         = $formattedAltitude;

                // Add summary string (e.g., "1.500 m² / 250 pohon")
                $farm->farm_summary = "{$formattedLand} / {$formattedTrees} pohon";

                return $farm;
            });

            // Dropdown Data
            $province = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], Province::listByName(""));

            $category = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], SupplyCategory::listByName(""));

            $bank = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], Bank::listByName(""));

            $coffee = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], Coffee::listByName(""));

            $coffeeVariety = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], CoffeeVariety::listByName(""));

            $shadeTree = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], ShadeTree::listByName(""));

            $landStatus = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], LandStatus::listByName(""));

            $businessType = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], BusinessType::listByName(""));

            $supplierStatus = [
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true]
            ];

            $evc = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], Evc::listByCode(""));

            $vch = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], VCH::listByCode(""));

            $vcp = array_merge([
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ], VCP::listByCode(""));

            foreach(config("constant.supplier_status") as $key => $val){
                $supplierStatus[] = [
                    'id' => $key, 'text' => $val
                ];
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return view("account.farmer-detail", compact("result", "province", "category", "bank", "supplierAsset", "farms", "coffee", "coffeeVariety", "shadeTree", "landStatus", "businessType", "supplierStatus", "evc", "vch", "vcp"));
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

    public function update(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $farmerCode = $request->input("farmer_code");
        $input = $request->except(["_token", "farmer_code"]);
        try{
            Supplier::where("code", $farmerCode)->update($input);
            CommonHelper::forgetCache("farmer");
            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return response()->json($response);
    }

    public function updateFarm(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $farmerCode = $request->input("farmer_code");
        $farmId = $request->input("farm_id");
        $input = $request->except(["_token", "farmer_code", "farm_id"]);
        try{\Log::debug($input);
            $supplier = Supplier::where("code", $farmerCode)->first();
            Farms::where([
                "supplier_id"   => $supplier->id,
                "id"            => $farmId
            ])->update($input);
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

    // ====================================================================================================
    //                                  for mobile or api section
    // ====================================================================================================

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
            $vchCode = $request->input("vch_code");

            $cacheName = "";
            if(!empty($vchCode)){
                if(is_array($vchCode) && (count($vchCode) > 0)){
                    $cacheName .= "vch_code_".implode("|", $vchCode).".";
                } else {
                    $response['data']["vch_code"] = "VCH code must be in array";
                    return response()->json($response);
                }
            }

            $response["count"] = (int)Cache::remember($cacheName."data.list.farmer.count", 120, function() use($vchCode){
                return Farmer::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->leftJoin("users", "master_supplier.user_id", "users.id")
                ->join("t_vch", "t_vch.id", "master_supplier.vch_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->when(count($vchCode) > 0, function($builder) use($vchCode){
                    return $builder->whereIn("t_vch.code", $vchCode);
                })
                ->count();
            });

            $response["data"] = Cache::remember($cacheName."data.list.farmer.page_$page.limit_$limit", 120, function() use($page, $limit, $vchCode){

                return Farmer::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->leftJoin("users", "master_supplier.user_id", "users.id")
                ->join("t_vch", "t_vch.id", "master_supplier.vch_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->when(!empty($page) && !empty($limit), function($builder) use($page, $limit){
                    return $builder->offset($page)->limit($limit);
                })
                ->when(count($vchCode) > 0, function($builder) use($vchCode){
                    return $builder->whereIn("t_vch.code", $vchCode);
                })
                ->select(DB::raw("master_supplier.code AS farmer_code, users.name, master_supplier.thumb_finger, master_supplier.index_finger, users.email, master_supplier.address, master_supplier.latitude, master_supplier.longitude, users.phone, master_supplier.id_number, sub_districts.code AS sub_district_code, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province, t_vch.code AS vch_code"))
                ->orderBy("master_supplier.created_at", "DESC")
                ->get();
            });

            $response["code"] = 200;
            $response["message"] = "Success";

        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function register(Request $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except(["_token"]);
        $file = $request->input('photo');
        $fileIdNumberImage = $request->input('id_number_image');
/*
        if(!empty($input["phone"]) && User::isPhoneExist($input["phone"])){
            $response["message"] = "Phone ".$input["phone"]." already registered, please use other number";
            return response()->json($response);
        }
*/
        if(!empty($input["id_number"]) && Farmer::isIdNumberExist($input["id_number"])){
            $response["message"] = "ID number ".$input["id_number"]." already registered.";
            return response()->json($response);
        }

        if(empty($input["vch_code"]) || empty($request->input("vch_code"))){
            $response["data"]["vch_code"] = "VCH Code is required.";
            return response()->json($response);
        }

        $vch = VCH::findByCode($input["vch_code"]);
        if(empty($vch)){
            $response["message"] = "VCH Code ".$input["vch_code"]." is registered.";
            return response()->json($response);
        } else {
            $input["vch_id"] = $vch->id;
            unset($input["vch_code"]);
        }

        if(!empty($file)){
            // validate image photo
            $result = CommonHelper::validateImage($file);
            if(!$result["is_valid"]){
                $response["message"] = $result["message"];
                return response()->json($response);
            }
            $input["file_type_photo"] = $result["file_type"];
        }

        if(!empty($fileIdNumberImage)){
            // validate image id number
            $result = CommonHelper::validateImage($fileIdNumberImage);
            if(!$result["is_valid"]){
                $response["message"] = $result["message"];
                return response()->json($response);
            }
            $input["file_type_id_number"] = $result["file_type"];
        }

        if(!empty($input["email"]) && User::isEmailExist($input["email"])){
            $response["message"] = "Email ".$input["email"]." already registered, please use other email.";
            return response()->json($response);
        }

        try{
            $prefix = Subdistrict::where("sub_districts.id", $input["sub_district_id"])
                ->select(DB::raw("sub_districts.code"))
                ->first();
            
            $farmer = Farmer::withTrashed()->where("sub_district_id", $input["sub_district_id"])
                ->orderBy("code", "DESC")->select("code")->first();

            if((array_key_exists("code", $input) && empty($input["code"])) || !(array_key_exists("code", $input))){
                if(empty($farmer)){
                    $input["code"] = $prefix->code.str_pad(1, 5, "0", STR_PAD_LEFT);
                } else {
                    $input["code"] = $prefix->code.str_pad((substr($farmer->code, 12) + 1), 5, "0", STR_PAD_LEFT);
                }
            }

            if (empty($input["email"])) {
                $count = User::count();
                $input["email"] = "farmer$count@gmail.com";
            }

            $dataUser = [
                "email" => $input["email"],
                "password" => Hash::make("password"),
                "name" => $input["name"],
                "phone" => empty($input["phone"]) ? "-" : $input["phone"],
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
                "vch_id"            => $input["vch_id"],
                "address"           => empty($input["address"]) ? "-" : $input["address"],
                "thumb_finger"      => array_key_exists("thumb_finger", $input) ? $input["thumb_finger"] : null,
                "index_finger"      => array_key_exists("index_finger", $input) ? $input["index_finger"] : null,
                "created_by"        => "Desktop App"
            ];
            
            if(!empty($file)){
                $fileName = date("Ymd")."_photo_".$input["id_number"].$input["file_type_photo"];
                Storage::disk("farmerId")->put($fileName, file_get_contents($file));
                $dataFarmer["image_photo_name"] = $fileName;
            }
            
            if(!empty($fileIdNumberImage)){
                $fileName = date("Ymd")."_id_number_".$input["id_number"].$input["file_type_id_number"];
                Storage::disk("farmerId")->put($fileName, file_get_contents($fileIdNumberImage));
                $dataFarmer["image_id_number_name"] = $fileName;
            }

            $farmer = Farmer::create($dataFarmer);
            $response["data"] = Farmer::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->leftJoin("users", "master_supplier.user_id", "users.id")
                ->join("t_vch", "t_vch.id", "master_supplier.vch_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where("master_supplier.id", $farmer->id)
                ->select(DB::raw("master_supplier.code AS farmer_code, users.name, master_supplier.thumb_finger, master_supplier.index_finger, users.email, master_supplier.address, master_supplier.latitude, master_supplier.longitude, users.phone, master_supplier.id_number, sub_districts.code AS sub_district_code, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province"))->orderBy("master_supplier.created_at", "DESC")->first();
            $response["code"] = 200;
            $response["message"] = "Success";
            CommonHelper::forgetCache("farmer");
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function _update(Request $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except(["_token"]);

        if(!Farmer::isIdNumberExist($input["id_number"])){
            $response["message"] = "ID number ".$input["id_number"]." is not registered.";
            return response()->json($response);
        }

        if(!empty($input["code"]) && empty(Farmer::findByCode($input["code"]))){
            $response["message"] = "Farmer code ".$input["code"]." is not registered.";
            return response()->json($response);
        }

        if(empty($input["vch_code"]) || empty($request->input("vch_code"))){
            // Nothing to do
        } else {
            $vch = VCH::findByCode($input["vch_code"]);
            if(empty($vch)){
                $response["message"] = "VCH Code ".$input["vch_code"]." is not registered.";
                return response()->json($response);
            } else {
                $input["vch_id"] = $vch->id;
                unset($input["vch_code"]);
            }
        }

        try{
            $prefix = Subdistrict::where("sub_districts.name", $input["sub_district"])
                ->select(DB::raw("sub_districts.code, sub_districts.id AS sub_district_id"))
                ->first();
            
            $farmer = Farmer::withTrashed()->where("sub_district_id", $prefix->sub_district_id)
                ->orderBy("code", "DESC")->select("code")->first();

            if((array_key_exists("code", $input) && empty($input["code"])) || !(array_key_exists("code", $input))){
                if(empty($farmer)){
                    $input["code"] = $prefix->code.str_pad(1, 5, "0", STR_PAD_LEFT);
                } else {
                    $input["code"] = $prefix->code.str_pad((substr($farmer->code, 12) + 1), 5, "0", STR_PAD_LEFT);
                }
            }

            if (empty($input["email"])) {
                $count = User::count();
                $input["email"] = "farmer$count@gmail.com";
            }
            
            $dataFarmer = [
                "code"              => $input["code"],
                "sub_district_id"   => $prefix->sub_district_id,
                "latitude"          => $input["latitude"],
                "longitude"         => $input["longitude"],
                "address"           => empty($input["address"]) ? "-" : $input["address"],
                "thumb_finger"      => array_key_exists("thumb_finger", $input) ? $input["thumb_finger"] : null,
                "index_finger"      => array_key_exists("index_finger", $input) ? $input["index_finger"] : null,
                "updated_by"        => "Desktop App",
                "updated_at"        => date("Y-m-d H:i:s")
            ];

            if(isset($input["vch_id"]) && !empty($input["vch_id"])){
                $dataFarmer["vch_id"] = $input["vch_id"];
            }

            Farmer::where([
                "id_number" => $input["id_number"]
            ])->update($dataFarmer);

            $response["data"] = Farmer::join("sub_districts", "sub_districts.id", "master_supplier.sub_district_id")
                ->leftJoin("users", "master_supplier.user_id", "users.id")
                ->join("t_vch", "t_vch.id", "master_supplier.vch_id")
                ->join("districts", "districts.id", "sub_districts.district_id")
                ->join("cities", "cities.id", "districts.city_id")
                ->join("provinces", "provinces.id", "cities.province_id")
                ->where([
                    "id_number" => $input["id_number"]
                ])
                ->select(DB::raw("master_supplier.code AS farmer_code, users.name, master_supplier.thumb_finger, master_supplier.index_finger, users.email, master_supplier.address, master_supplier.latitude, master_supplier.longitude, users.phone, master_supplier.id_number, sub_districts.code AS sub_district_code, sub_districts.name AS sub_district, districts.name AS district, cities.name AS city, provinces.name AS province, t_vch.code AS vch_code"))->orderBy("master_supplier.created_at", "DESC")->first();
            $response["code"] = 200;
            $response["message"] = "Success";
            CommonHelper::forgetCache("farmer");
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
                    $chaincodeName = config("constant.fabric.chaincode.farmer_private");
                    $channel = config("constant.fabric.channel");

                    if(array_key_exists("org_msp", $input) && !empty($input["org_msp"])){
                        if($input["org_msp"] == "export"){
                            $readAsset = new ExportReadAsset();
                            $response["data"] = $readAsset->public($input["transaction_id"], $channel, $chaincodeName);
                        }
                    } else {
                        $readAsset = new FarmerReadAsset();
                        $response["data"] = $readAsset->public($input["transaction_id"], $channel, $chaincodeName);
                    }
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
                $chaincodeName = config("constant.fabric.chaincode.farmer_private");
                $mspPrivateCollection = "FarmerMSPPrivateCollection";

                try{
                    if(array_key_exists("org_msp", $input) && !empty($input["org_msp"]) && in_array($input["org_msp"], ["farmer", "pulper", "huller", "headoffice"])){
                        if($input["org_msp"] == "pulper"){
                            $readAsset = new PulperReadAsset();
                            $response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection, $chaincodeName);
                        } else if($input["org_msp"] == "huller"){
                            $readAsset = new HullerReadAsset();
                            $response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection, $chaincodeName);
                        } else if($input["org_msp"] == "headoffice"){
                            $readAsset = new HeadOfficeReadAsset();
                            $response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection, $chaincodeName);
                        } else {
                            $readAsset = new FarmerReadAsset();
                            $response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection, $chaincodeName);
                        }
                    } else {
                        //$readAsset = new FarmerReadAsset();
                        //$response["data"] = $readAsset->private($input["transaction_id"], $mspPrivateCollection, $chaincodeName);
                    }
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
