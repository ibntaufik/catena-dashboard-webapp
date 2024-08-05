<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Location;
use App\Http\Requests\LocationPostRequest;
use App\Http\Requests\RemoveLocationPostRequest;

class LocationController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        return view("master-data.location");
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
            $response["data"] = Location::get();
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
            $input["created_by"] = "Admin";
            Location::create($input);
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
            Location::where("code", $request->input("location_id"))->delete();

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
}
