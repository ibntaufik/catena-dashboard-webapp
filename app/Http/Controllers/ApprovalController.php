<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Helpers\CommonHelper;
use App\Http\Requests\SelectedPostRequest;
use App\Model\Approval;
use App\Model\User;

class ApprovalController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        $candidate = $this->collectCandidate();
        return view("master-data.ho-approval", compact('candidate'));
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
            $response["data"] = Approval::join("users", "users.id", "ho_approval.user_id")->orderBy("ho_approval.created_at", "DESC")->select("users.id", "users.name")->get();
        } catch(\Exception $e){
            
        }
        
        return response()->json($response);
    }

    public function save(SelectedPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $input = $request->except(["_token"]);

            // check if already exist on db, only after deleted
            $isExist = Approval::onlyTrashed()->where("user_id", $input)->first();
            if(empty($isExist)){
                $input["created_by"] = Auth::user()->name;
                Approval::create($input);
                CommonHelper::forgetCache("approval");
            } else {
                $isExist->deleted_at = null;
                $isExist->updated_at = now();
                $isExist->updated_by = "Admin";
                $isExist->save();
            }
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }

    public function delete(SelectedPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            Approval::where("user_id", $request->input("user_id"))->delete();

            CommonHelper::forgetCache("approval");
            $response["code"] = 200;
            $response["message"] = "Success";
            $response["data"] = $this->collectCandidate();
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    function collectCandidate(){

        return Cache::remember("approval.candidat", config("constant.ttl"), function(){

            $candidate = [
                ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
            ];

            $approver = Approval::select("user_id")->get()->toArray();

            $result = User::join("ho_account", "ho_account.user_id", "users.id")
            ->when(count($approver) > 0, function($builder) use($approver){
                return $builder->whereNotIn("users.id", $approver);
            })->select(DB::raw("users.id, name AS text"))->get()->toArray();
            $candidate = array_merge($candidate, $result);

            return $candidate;
        });
    }
}
