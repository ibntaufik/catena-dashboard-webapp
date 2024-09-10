<?php

namespace App\Http\Controllers;

use App\Helpers\CommonHelper;
use App\Model\Farmer;
use App\Model\PurchaseOrder;
use App\Model\PurchaseOrderTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function __construct(){
        
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
            $vchCode = $request->input("vch_code");

            if(empty($vchCode)){
                $response["message"] = "VCH user cannot be empty.";
            } else {

                $cacheName = "";

                if(is_array($vchCode) && (count($vchCode) > 0)){
                    $cacheName .= "vch_code_".implode("|", $vchCode).".";
                } else {
                    $response['message'] = "VCH code must be in array";
                    return response()->json($response);
                }

                $response["count"] = Cache::remember("count.purchase_order_transaction.$cacheName", 120, function() use($vchCode){
                    return PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("account_farmer", "purchase_order_transaction.account_farmer_id", "account_farmer.id")
                    ->join("users", "users.id", "account_farmer.user_id")
                    ->join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when(count($vchCode) > 0, function($builder) use($vchCode){
                        return $builder->whereIn("t_vch.code", $vchCode);
                    })
                    ->count();
                });

                $response["data"] = Cache::remember("list.purchase_order_transaction.$cacheName", 120, function() use($vchCode, $page, $limit){
                    return PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("account_farmer", "purchase_order_transaction.account_farmer_id", "account_farmer.id")
                    ->join("users", "users.id", "account_farmer.user_id")
                    ->join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when(count($vchCode) > 0, function($builder) use($vchCode){
                        return $builder->whereIn("t_vch.code", $vchCode);
                    })
                    ->select(DB::raw("transaction_id, receipt_number, transaction_date, floating_rate, po_number, users.name AS farmer_name, account_farmer.code AS farmer_code, total_item_price AS total_price"))
                    ->get();
                });

                if($response["count"] > 0){
                    $response["code"] = 200;
                    $response["message"] = "Success";
                } else {
                    $response["code"] = 404;
                    $response["message"] = "Not found";
                }
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function submit(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];

        $input = $request->except("_token");

        try{
            $emptyResponse = (object) array();
            $input = $request->except('');
            $paramFailed = array();
            $required = ['po_number', 'farmer_code', 'transaction_id', 'transaction_date', 'floating_rate'];

            foreach ($required as $item) {
                if (!array_key_exists($item, $input)) $paramFailed[] = $item;
            }

            if (!empty($paramFailed)) {
                $message = "Missing Parameter : " . implode(', ', $paramFailed).".";
                $response["message"] = $message; 
                $response["data"] = $emptyResponse;
            } else {
                $poNumber = $request->input("po_number");
                $farmerCode = $request->input("farmer_code");
                $transactionId = $request->input("transaction_id");
                $receiptNumber = $request->input("receipt_number");
                $transactionDate = $request->input("transaction_date");
                $floatingRate = $request->input("floating_rate");
                $totalItemPrice = $request->input("total_item_price");
                $itemQuantity = $request->input("item_quantity");
                $itemPrice = 0;
                $pass = true;

                if(empty($poNumber)){
                    $response["message"] = "PO Number cannot be empty.";
                    $pass = false;
                } else {
                    $po = PurchaseOrder::where(["po_number" => $poNumber])->first();
                    if(empty($po)){
                        $response["message"] = "PO Number $poNumber is not registered.";
                        $pass = false;
                    } else if($po->status != "approved"){
                        $response["message"] = "Status PO Number $poNumber is ".$po->status.".";
                        $pass = false;
                    } else {
                        $input["purchase_order_id"] = $po->id;
                        $itemPrice = $po->item_unit_price;
                        unset($input["po_number"]);
                    }
                    
                }
                if(empty($farmerCode)){
                    $response["message"] = "Farmer code cannot be empty.";
                    $pass = false;
                } else {
                    $farmer = Farmer::findByCode($farmerCode);
                    if(empty($farmer)){
                        $response["message"] = "Farmer code $farmerCode is not registered.";
                        $pass = false;
                    }

                    $input["account_farmer_id"] = $farmer->id;
                    unset($input["farmer_code"]);
                }

                if(empty($transactionId)){
                    $response["message"] = "Transaction Id cannot be empty.";
                    $pass = false;
                } else {
                    $isExist = PurchaseOrderTransaction::where("transaction_id", $transactionId)->first();
                    if(!empty($isExist)){
                        $response["message"] = "Transaction Id $transactionId have been registered.";
                        $pass = false;
                    }
                }
                
                $formatDate = "d/m/Y";
                if(empty($transactionDate)){
                    $response["message"] = "Transaction date cannot be empty.";
                    $pass = false;
                } else if(!CommonHelper::isValidDate($transactionDate, $formatDate)){
                    $response["message"] = "Format date must be $formatDate.";
                    $pass = false;
                } else {
                    // parse date to Y-m-d format
                    $input["transaction_date"] = CommonHelper::parseDate($transactionDate, $formatDate);
                }

                if(empty($floatingRate) || ($floatingRate < 1)){
                    $response["message"] = "Floating rate minimum 1.";
                    $pass = false;
                }

                if(empty($itemQuantity) || ($itemQuantity < 1)){
                    $response["message"] = "Item quantity minimum 1.";
                    $pass = false;
                } else {
                    if(empty($po)){
                        $po = PurchaseOrder::where(["po_number" => $poNumber])->first();
                    }

                    $calcTotalQuantityPo = PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->where("purchase_order.id", $po->id)
                    ->select(DB::raw("SUM(purchase_order_transaction.item_quantity) AS weight_fulfilled"))->first();

                    $calcTotalQuantityPo->weight_fulfilled = ($calcTotalQuantityPo->weight_fulfilled == null) ? 0 : $calcTotalQuantityPo->weight_fulfilled;
                    $allowedQuantity = $po->item_max_quantity - $calcTotalQuantityPo->weight_fulfilled;

                    if($allowedQuantity < $itemQuantity){
                        $response["message"] = "Item quantity that can be entered is ".($allowedQuantity).".";
                        $pass = false;
                    }
                }

                if($pass){
                    $input["total_item_price"] = $floatingRate * $itemPrice;
                    $trx = PurchaseOrderTransaction::create($input);

                    $poTrx = PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("account_farmer", "purchase_order_transaction.account_farmer_id", "account_farmer.id")
                    ->join("users", "users.id", "account_farmer.user_id")
                    ->where("purchase_order_transaction.id", $trx->id)
                    ->select(DB::raw("transaction_id, receipt_number, transaction_date, floating_rate, po_number, users.name AS farmer_name, account_farmer.code AS farmer_code, total_item_price AS total_price"))
                    ->first();

                    $response["code"] = 200;
                    $response["message"] = "Transaction have been created.";
                    $response["data"] = $poTrx;
                }
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        } finally {
            CommonHelper::forgetCache("purchase_order_transaction");
            CommonHelper::forgetCache("po");
        }
        
        return response()->json($response);
    }
}
