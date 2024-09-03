<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Helpers\CommonHelper;
use App\Http\Requests\AccountPostRequest;
use App\Model\Account;
use App\Model\User;

class AccountController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $statusAccount = [
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ];

        $accountStatus = config("constant.account_status");
        foreach ($accountStatus as $key => $value) {
            $statusAccount[] = [
                "id"    => $key,
                "text"  => $value
            ];
        }
        return view("master-data.account.index", compact("statusAccount"));
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

            $response["data"] = Account::join("users", "accounts.user_id", "users.id")->select(DB::raw("users.name, users.email, users.phone, accounts.code"))->orderBy("accounts.created_at", "DESC")->get();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(AccountPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        $input = $request->except(["_token"]);
        
        if(User::isEmailExist($input["email"])){
            $response["message"] = "Email already registered, please use other email";
            return response()->json($response);
        }

        if(User::isPhoneExist($input["phone"])){
            $response["message"] = "Phone already registered, please use other phone number";
            return response()->json($response);
        }

        if(!empty(Account::findByCode($input["user_id"]))){
            $response["message"] = "Account with code ".input["phone"]." already registered.";
            return response()->json($response);
        }
        
        try{    
            $dataUser = [
                "email"     => $input["email"],
                "password"  => Hash::make($input["password"]),
                "name"      => $input["name"],
                "phone"     => $input["phone"]
            ];

            $user = User::create($dataUser);
            Account::create([
                "user_id"       => $user->id,
                "code"          => $input["user_id"],
                "created_by"    => "System Administrator"
            ]);

            CommonHelper::forgetCache("account");

            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
