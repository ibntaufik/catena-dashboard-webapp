<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\VCHPostRequest;
use App\Http\Requests\RemoveVCHPostRequest;
use App\Model\Location;
use App\Model\Bank;
use App\Model\VCH;
use App\Model\User;

class VCHController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        // Get list of Location
        $candidate = [
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ];

        $result = Location::select(DB::raw("code, sub_district"))->get()->toArray();
        $result = collect($result)->map(function ($item) {
            return ["id" => $item['code'], "text" => $item['sub_district']];
        });
        $candidate = array_merge($candidate, json_decode($result, true));

        // Get list of Bank
        $bank = [
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ];
        $list = Bank::select(DB::raw("code, name"))->get()->toArray();
        $list = collect($list)->map(function ($item) {
            return ["id" => $item['code'], "text" => $item['name']];
        });
        $bank = array_merge($bank, json_decode($list, true));

        return view("account.vch", compact("candidate", "bank"));
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
            $response["data"] = VCH::leftJoin("location", "location.id", "vch_account.location_id")->leftJoin("bank", "vch_account.bank_id", "bank.id")->leftJoin("users", "vch_account.user_id", "users.id")->select(DB::raw("vch_account.vch_code, users.email, location.sub_district, vch_account.address, vch_account.latitude, vch_account.longitude, vch_account.vendor_id, vch_account.vendor_name, bank.name AS vendor_bank_name, vch_account.vendor_bank_address, vch_account.vendor_bank_account_number"))->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(VCHPostRequest $request){
        
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
                "name" => $input["vendor_name"],
                "created_by" => "System Administrator"
            ];

            $user = User::create($dataUser);
            
            $input["created_by"] = "Admin";
            $location = Location::findByCode($input["location_code"]);
            $input["location_id"] = $location->id;
            $input["user_id"]  = $user->id;
            $bank = Bank::findByCode($input["location_code"]);
            $input["bank_id"] = $location->id;

            unset($input["location_code"]);

            VCH::create($input);
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCP ".$input["vch_code"];
        }
        
        return response()->json($response);
    }

    public function delete(RemoveVCHPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            VCH::where("vch_code", $request->input("vch_code"))->delete();

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
