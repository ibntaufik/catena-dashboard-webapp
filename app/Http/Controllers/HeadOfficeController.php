<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeadOfficeController extends Controller
{
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
                $mspPrivateCollection = "HeadOfficeMSPPrivateCollection";
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
