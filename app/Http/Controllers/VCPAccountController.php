<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Http\Requests\VcpAccountPostRequest;
use App\Http\Requests\RemoveVCPPostRequest;
use App\Model\Account;
use App\Model\User;
use App\Model\VcpAccount;
use App\Model\VCP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VcpAccountController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $vcp = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(VCP::listCombo(), true));

        $account = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(Account::listFieldCoordinator()));

        return view("account.vcp", compact("vcp", "account"));
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
            $response["data"] = VcpAccount::join("t_vcp", "account_vcp.vcp_id", "t_vcp.id")
            ->join("t_vch", "t_vch.id", "t_vcp.vch_id")
            ->join("t_evc", "t_evc.id", "t_vch.evc_id")
            ->join("accounts", "account_vcp.account_id", "accounts.id")
            ->join("users", "accounts.user_id", "users.id")
            ->join("sub_districts", "sub_districts.id", "t_vcp.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("t_evc.code AS evc_code, t_vch.code AS vch_code, t_vcp.code AS vcp_code, users.email, CONCAT(sub_districts.name, ' <br> ', districts.name, ' <br> ', cities.name, ' <br> ', provinces.name) AS location, t_vcp.address, t_vcp.latitude, t_vcp.longitude, accounts.code AS field_coordinator_id, users.name AS field_coordinator_name"))->orderBy("t_vcp.code", "ASC")->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(VcpAccountPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        $input = $request->except(["_token"]);
        
        try{
            $vcp = VCP::findActiveByCode($input["vcp_code"]);
            if(empty($vcp)){
                $response["message"] = "VCP Code ".$input["vcp_code"]." is not listed on system";
                return response()->json($response);
            }

            $account = Account::findActiveByCode($input["account_code"]);
            if(empty($account)){
                $response["message"] = "Account with code ".$input["account_code"]." is not listed on system";
                return response()->json($response);
            }

            // Check if selected account and master data already in db.
            $isExist = VcpAccount::where([
                "account_id"    => $account->id,
                "vcp_id"        => $vcp->id,
            ])->select("id")->first();
            if(!empty($isExist)){
                $response["message"] = "Account code ".$input["account_code"]." with VCP code ".$input["vcp_code"]." already listed on system";
                return response()->json($response);
            }

            VcpAccount::create([
                "vcp_id" => $vcp->id,
                "account_id" => $account->id
            ]);
            
            CommonHelper::forgetCache("vcp");

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
            CommonHelper::forgetCache("vcp");
            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
