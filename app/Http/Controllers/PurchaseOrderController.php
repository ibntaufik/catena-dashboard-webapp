<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Model\Item;
use App\Model\ItemType;
use App\Model\ItemUnit;
use App\Model\PurchaseOrder;
use App\Model\VCH;
use App\Model\VchAccount;
use App\Helpers\CommonHelper;
use App\Http\Requests\PurchaseOrderPostRequest;
use App\Http\Requests\RemovePurchaseOrderPostRequest;

class PurchaseOrderController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){

        $vch = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], VchAccount::combo());

        $item = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], Item::listCombo());

        $itemUnit = array_merge([
            ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
        ], ItemUnit::listCombo());

        return view("transactions.purchase-order.create", compact("vch", "item", "itemUnit"));
    }

    public function release(Request $request){
        return view("transactions.purchase-order.approval");
    }

    public function datatables(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $status = $request->input("status", "all");

        try{
            $response["code"] = 200;
            $response["message"] = "Success";
            $response["data"] = Cache::remember("datalist.purchase_order.status_$status", config("constant.ttl"), function() use($status){
                return PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when(!empty($status) && in_array($status, ["waiting", "approved", "rejected"]), function($builder) use($status){
                        return $builder->where("purchase_order.status", $status);
                    })
                    //->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    //->join("sub_districts", "sub_districts.id", "t_vcp.sub_district_id")
                    //->join("districts", "districts.id", "sub_districts.district_id")
                    //->join("cities", "cities.id", "districts.city_id")
                    //->join("provinces", "provinces.id", "cities.province_id")
                    ->select(DB::raw("t_vch.code AS vch_code, CONCAT('(', accounts.code, ') ', users.name) AS vendor, po_number, po_date, expected_shipping_date, item.name AS item_name, item_type.name AS item_type, item_unit.name AS item_unit, item_description, item_quantity, item_unit_price, item_max_quantity, purchase_order.status"))->get();
            });
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function save(PurchaseOrderPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $input = $request->except(["_token"]);

            if(!empty(PurchaseOrder::findByPoNumber($input["po_number"]))){
                throw new \Exception("PO Number ".$input["po_number"]." already registered, please input other PO Number");
            }

            PurchaseOrder::create($input);
            CommonHelper::forgetCache("datalist.purchase_order");
            $response["code"] = 200;
            $response["message"] = "Success";
            
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }

    public function delete(RemovePurchaseOrderPostRequest $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            PurchaseOrder::where("po_number", $request->input("po_number"))->delete();
            CommonHelper::forgetCache("datalist.purchase_order");

            $response["code"] = 200;
            $response["message"] = "Success";
        } catch(\Exception $e){
            \Log::error($e->getTraceAsString());
            $response["message"] = $e->getMessage();
        }
        
        return response()->json($response);
    }
}
