<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CommonHelper;
use App\Http\Requests\VchAccountPostRequest;
use App\Http\Requests\RemoveVchAccountPostRequest;
use App\Model\Account;
use App\Model\Bank;
use App\Model\VCH;
use App\Model\VchAccount;
use App\Model\User;

class VchAccountController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        // List of VCH
        $vch = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(VCH::listCombo("")));
        
        // List of Account
        $account = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode(Account::listVendor(), true));

        // Get list of Bank
        $bank = Bank::select(DB::raw("code, name"))->get()->toArray();
        $list = collect($bank)->map(function ($item) {
            return ["id" => $item['code'], "text" => $item['name']];
        });
        $bank = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], json_decode($list, true));

        return view("account.vch", compact("vch", "account", "bank"));
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
            $response["data"] = VchAccount::join("t_vch", "account_vch.vch_id", "t_vch.id")
            ->join("accounts", "account_vch.account_id", "accounts.id")
            ->join("users", "accounts.user_id", "users.id")
            ->leftJoin("bank", "account_vch.bank_id", "bank.id")
            ->join("sub_districts", "sub_districts.id", "t_vch.sub_district_id")
            ->join("districts", "districts.id", "sub_districts.district_id")
            ->join("cities", "cities.id", "districts.city_id")
            ->join("provinces", "provinces.id", "cities.province_id")
            ->select(DB::raw("t_vch.code AS vch_code, users.email, CONCAT(sub_districts.name, ' <br> ', districts.name, ' <br> ', cities.name, ' <br> ', provinces.name) AS location, t_vch.address, t_vch.latitude, t_vch.longitude, accounts.code AS vendor_code, users.name AS vendor_name, bank.name AS vendor_bank_name, account_vch.vendor_bank_address, account_vch.vendor_bank_account_number"))->orderBy("t_vch.code", "ASC")->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(VchAccountPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        $input = $request->except(["_token"]);

        try{

            $vch = VCH::findActiveByCode($input["vch_code"]);
            if(empty($vch)){
                $response["message"] = "VCH Code ".$input["vch_code"]." is not listed on system";
                return response()->json($response);
            }

            $account = Account::findActiveByCode($input["account_code"]);
            if(empty($account)){
                $response["message"] = "Account with code ".$input["account_code"]." is not listed on system";
                return response()->json($response);
            }

            // Check if selected account and master data already in db.
            $isExist = VchAccount::where([
                "account_id"    => $account->id,
                "vch_id"        => $vch->id,
            ])->select("id")->first();
            if(!empty($isExist)){
                $response["message"] = "Account code ".$input["account_code"]." with VCH code ".$input["vch_code"]." already listed on system";
                return response()->json($response);
            }

            $bank = Bank::findActiveByCode($input["bank_code"]);
            if(empty($account)){
                $response["message"] = "Bank ".$input["bank_code"]." is not listed on system";
                return response()->json($response);
            }
            
            $input["created_by"] = "Admin";
            $input["account_id"]  = $account->id;
            $input["vch_id"]  = $vch->id;
            $input["bank_id"] = $bank->id;
            $input["created_by"] = Auth::user()->name;
            VchAccount::create($input);

            CommonHelper::forgetCache("account");
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = "Failed to save VCH Account with code ".$input["vch_code"];
        }
        
        return response()->json($response);
    }

    public function delete(RemoveVchAccountPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $vch = VCH::findByCode($request->input("vch_code"));
            if(empty($vch)){
                $response["message"] = "VCH Code ".$input["vch_code"]." is not listed on system";
                return response()->json($response);
            }

            $account = Account::findByCode($request->input("vendor_code"));
            if(empty($account)){
                $response["message"] = "Account with code ".$input["vendor_code"]." is not listed on system";
                return response()->json($response);
            }

            VchAccount::where([
                "vch_id" => $vch->id,
                "account_id" => $account->id
            ])->update([
                "deleted_at" => date("Y-m-d H:i:s")
            ]);
            CommonHelper::forgetCache("account");
            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::debug($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }
}
