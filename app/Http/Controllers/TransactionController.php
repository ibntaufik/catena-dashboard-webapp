<?php

namespace App\Http\Controllers;

use DateTime;
use App\Helpers\CommonHelper;
use App\Model\Account;
use App\Model\Farmer;
use App\Model\User;
use App\Model\HOAccount;
use App\Model\PurchaseOrder;
use App\Model\PurchaseOrderTransaction;
use App\Model\VCP;
use App\Model\VcpAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function __construct(){
        
    }

    public function index(Request $request){
        return view("transactions.purchase-order.transaction");
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
            $status = $request->input("status");
            
            if(!Auth::check() && empty($vchCode)){
                $response["message"] = "VCH user cannot be empty.";
            } else {
                $isHOUser = null;
                if(Auth::check()){
                    $isHOUser = HOAccount::findByUserId(Auth::user()->id);
                }
                $cacheName = "";
                if(empty($isHOUser)){
                    if(is_array($vchCode) && (count($vchCode) > 0)){
                        $cacheName .= "vch_code_".implode("|", $vchCode).".";
                    } else {
                        $response['message'] = "VCH code must be in array";
                        return response()->json($response);
                    }
                } else {
                    if(!empty($status)){
                        $cacheName .= "status_$status";
                    }
                    $vchCode = [];
                }

                if($page){
                    $cacheName .= ".page|$page";
                }
                
                $farmer = $request->input("farmer_name");
                if($farmer){
                    $cacheName .= ".farmer_name|$farmer";
                }
                
                $vcpCode = $request->input("vcp_code");
                if($vcpCode){
                    $cacheName .= ".vcp_code|$vcpCode";
                }
                
                $transactionId = $request->input("transaction_id");
                if($transactionId){
                    $cacheName .= ".transaction_id|$transactionId";
                }
                
                $poNumber = $request->input("po_number");
                if($poNumber){
                    $cacheName .= ".po_number|$poNumber";
                }
                
                $receiptNumber = $request->input("receipt_number");
                if($receiptNumber){
                    $cacheName .= ".receipt_number|$receiptNumber";
                }
                
                $itemType = $request->input("item_type");
                if($itemType){
                    $cacheName .= ".item_type|$itemType";
                }
                
                $floatingRate = $request->input("floating_rate");
                if($floatingRate){
                    $cacheName .= ".floating_rate|$floatingRate";
                }
                
                $itemPrice = $request->input("item_price");
                if($itemPrice){
                    $cacheName .= ".item_price|$itemPrice";
                }
                
                $itemQuantity = $request->input("item_quantity");
                if($itemQuantity){
                    $cacheName .= ".item_quantity|$itemQuantity";
                }

                $totalPrice = $request->input("total_price");
                if($totalPrice){
                    $cacheName .= ".total_price|$totalPrice";
                }

                $dateRange = $request->input("daterange_transaction");
                $startDate = "";
                $endDate = "";
                if($dateRange){
                    $dateRange = explode(" to ",$dateRange);
                    $startDate = DateTime::createFromFormat('d-m-Y', $dateRange[0])->format('Y-m-d');
                    $endDate = DateTime::createFromFormat('d-m-Y', $dateRange[1])->format('Y-m-d');

                    $cacheName .= ".start_date|$startDate";
                    $cacheName .= ".end_date|$endDate";
                }

                $response["count"] = Cache::remember("count.purchase_order_transaction$cacheName", 120, function() use($vchCode, $status, $farmer, $vcpCode, $transactionId, $poNumber, $receiptNumber, $itemType, $floatingRate, $itemPrice, $itemQuantity, $totalPrice, $startDate, $endDate){
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
                    ->when($farmer, function($builder) use($farmer){
                        return $builder->whereRaw("UPPER(users.name) LIKE ?", [strtoupper("%$farmer%")]);
                    })
                    ->when($vcpCode, function($builder) use($vcpCode){
                        return $builder->join("t_vcp", "t_vcp.id", "purchase_order_transaction.vcp_id")
                        ->where("t_vcp.code", $vcpCode);
                    })
                    ->when($transactionId, function($builder) use($transactionId){
                        return $builder->where("purchase_order_transaction.transaction_id", $transactionId);
                    })
                    ->when($poNumber, function($builder) use($poNumber){
                        return $builder->where("purchase_order.po_number", $poNumber);
                    })
                    ->when($receiptNumber, function($builder) use($receiptNumber){
                        return $builder->where("purchase_order_transaction.receipt_number", $receiptNumber);
                    })
                    ->when($receiptNumber, function($builder) use($receiptNumber){
                        return $builder->where("purchase_order_transaction.receipt_number", $receiptNumber);
                    })
                    ->when($itemType, function($builder) use($itemType){
                        return $builder->whereRaw("UPPER(name) LIKE ?", [strtoupper("$itemType%")]);
                    })
                    ->when($floatingRate, function($builder) use($floatingRate){
                        return $builder->where("purchase_order_transaction.floating_rate", $floatingRate);
                    })
                    ->when($itemPrice, function($builder) use($itemPrice){
                        return $builder->where("purchase_order_transaction.item_price", $itemPrice);
                    })
                    ->when($itemQuantity, function($builder) use($itemQuantity){
                        return $builder->where("purchase_order_transaction.item_quantity", $itemQuantity);
                    })
                    ->when($totalPrice, function($builder) use($totalPrice){
                        return $builder->where("purchase_order_transaction.total_item_price", $totalPrice);
                    })
                    ->when($startDate && $endDate, function($builder) use($startDate, $endDate){
                        return $builder->whereBetween("purchase_order_transaction.transction_date", [$startDate, $endDate]);
                    })
                    ->when($status, function($builder) use($status){
                        return $builder->where("purchase_order_transaction.status", $status);
                    })
                    ->count();
                });

                $response["data"] = Cache::remember("list.purchase_order_transaction$cacheName", 120, function() use($vchCode, $status, $farmer, $vcpCode, $transactionId, $poNumber, $receiptNumber, $itemType, $floatingRate, $itemPrice, $itemQuantity, $totalPrice, $startDate, $endDate, $page, $limit){
                    $transaction = PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
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
                    ->when($status, function($builder) use($status){
                        return $builder->where("purchase_order_transaction.status", $status);
                    })
                    ->when($farmer, function($builder) use($farmer){
                        return $builder->whereRaw("UPPER(users.name) LIKE ?", [strtoupper("$farmer%")]);
                    })
                    ->when($vcpCode, function($builder) use($vcpCode){
                        return $builder->join("t_vcp", "t_vcp.id", "purchase_order_transaction.vcp_id")
                        ->where("t_vcp.code", $vcpCode);
                    })
                    ->when($transactionId, function($builder) use($transactionId){
                        return $builder->where("purchase_order_transaction.transaction_id", $transactionId);
                    })
                    ->when($poNumber, function($builder) use($poNumber){
                        return $builder->where("purchase_order.po_number", $poNumber);
                    })
                    ->when($receiptNumber, function($builder) use($receiptNumber){
                        return $builder->where("purchase_order_transaction.receipt_number", $receiptNumber);
                    })
                    ->when($receiptNumber, function($builder) use($receiptNumber){
                        return $builder->where("purchase_order_transaction.receipt_number", $receiptNumber);
                    })
                    ->when($itemType, function($builder) use($itemType){
                        return $builder->whereRaw("UPPER(name) LIKE ?", [strtoupper("$itemType%")]);
                    })
                    ->when($floatingRate, function($builder) use($floatingRate){
                        return $builder->where("purchase_order_transaction.floating_rate", $floatingRate);
                    })
                    ->when($itemPrice, function($builder) use($itemPrice){
                        return $builder->where("purchase_order_transaction.item_price", $itemPrice);
                    })
                    ->when($itemQuantity, function($builder) use($itemQuantity){
                        return $builder->where("purchase_order_transaction.item_quantity", $itemQuantity);
                    })
                    ->when($totalPrice, function($builder) use($totalPrice){
                        return $builder->where("purchase_order_transaction.total_item_price", $totalPrice);
                    })
                    ->when($page, function($builder) use($page){
                        return $builder->skip($page);
                    })
                    ->when($limit, function($builder) use($limit){
                        return $builder->take($limit);
                    })
                    ->select(DB::raw("transaction_id, vcp_id, receipt_number, purchase_order_transaction.status, transaction_date, floating_rate, po_number, users.name AS farmer_name, account_farmer.code AS farmer_code, item_type.name AS item_type, purchase_order_transaction.item_price AS item_price, purchase_order_transaction.item_quantity, total_item_price AS total_price"))
                    ->orderBy("purchase_order_transaction.created_at", "DESC")
                    ->get();

                    foreach ($transaction as $key => $value) {
                        $vcp = VCP::findById($value->vcp_id);

                        $transaction[$key]["vcp_code"] = $vcp->vcp_code;
                    }

                    return $transaction;
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
                $itemPrice = $request->input("item_price");
                $pass = true;

                if(!array_key_exists("vcp_code", $input) || (array_key_exists("vcp_code", $input) && empty($input["vcp_code"]))){
                    $response["message"] = "VCP Code cannot be empty.";
                    $pass = false;
                } else {
                    $vcp = VCP::findByVcpCode($input["vcp_code"]);
                    if(empty($vcp)){
                        $response["message"] = "VCP Code ".$input["vcp_code"]." is not listed on system.";
                        $pass = false;
                    }

                    $input["vcp_id"] = $vcp->id;
                    unset($input["vcp_code"]);
                }

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
                
                $formatDate = "d/m/Y H:i:s";
                if(empty($transactionDate)){
                    $response["message"] = "Transaction date cannot be empty.";
                    $pass = false;
                } else if(!CommonHelper::isValidDate($transactionDate, $formatDate)){
                    $response["message"] = "Format date must be $formatDate.";
                    $pass = false;
                } else {
                    // parse date to Y-m-d format
                    $input["transaction_date"] = CommonHelper::parseDate($transactionDate, $formatDate, "Y-m-d H:i:s");
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

                if(empty($itemPrice) || ($itemPrice < 1)){
                    $response["message"] = "Item price minimum 1.";
                    $pass = false;
                }

                if($pass){
                    $input["total_item_price"] = $itemQuantity * $itemPrice;
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
                    
                    CommonHelper::forgetCache("purchase_order_transaction");
                    CommonHelper::forgetCache("po");
                }
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function listByUser(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "count"     => 0,
            "data"      => []
        ];

        try{
            $page = $request->input("start");
            $limit = $request->input("limit");
            $userId = $request->input("vcp_user_id");
            $status = $request->input("status");
            
            if(!Auth::check() && empty($userId)){
                $response["message"] = "VCP user id cannot be empty.";
            } else {
                $cacheName = "";
                if(!empty($status)){
                    $cacheName .= "status_$status";
                }

                if(!empty($cacheName)){
                    $cacheName .= "|";
                }
                if(!empty($userId)){
                    $cacheName .= "userId_$userId";
                }

                $response["count"] = 0 + Cache::remember("count.purchase_order_transaction.$cacheName", 120, function() use($status, $userId){
                    return PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("users", "users.id", "purchase_order_transaction.vcp_user_id")
                    ->join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->when($status, function($builder) use($status){
                        return $builder->where("purchase_order_transaction.status", $status);
                    })
                    ->when($userId, function($builder) use($userId){
                        return $builder->where("purchase_order_transaction.vcp_user_id", $userId);
                    })
                    ->count();
                });

                $response["data"] = Cache::remember("list.purchase_order_transaction.$cacheName", 120, function() use($status, $userId, $page, $limit){
                    $transaction = PurchaseOrderTransaction::join("purchase_order", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("users", "users.id", "purchase_order_transaction.vcp_user_id")
                    ->join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->when($status, function($builder) use($status){
                        return $builder->where("purchase_order_transaction.status", $status);
                    })
                    ->when($userId, function($builder) use($userId){
                        return $builder->where("purchase_order_transaction.vcp_user_id", $userId);
                    })
                    ->select(DB::raw("po_number, users.name AS farmer_name, transaction_id, receipt_number, account_farmer_id, purchase_order_transaction.item_quantity, purchase_order_transaction.item_price AS item_price, transaction_date, floating_rate, purchase_order_transaction.vcp_id, users.id AS vcp_user_id"))
                    ->groupBy(DB::raw("po_number, users.name, transaction_id, receipt_number, account_farmer_id, purchase_order_transaction.item_quantity, purchase_order_transaction.item_price, transaction_date, floating_rate, users.id, purchase_order_transaction.created_at, purchase_order_transaction.vcp_id"))
                    ->orderBy("purchase_order_transaction.created_at", "DESC")
                    ->get();

                    foreach ($transaction as $key => $value) {

                        $vcp = VCP::findById($value->vcp_id);
                        $transaction[$key]["vcp_code"] = $vcp->vcp_code;
                        unset($transaction[$key]["vcp_id"]);

                        $farmer = Farmer::findById($value->account_farmer_id);
                        $user = User::findById($farmer->user_id);
                        $transaction[$key]["farmer_name"] = $user->name;
                        $transaction[$key]["farmer_code"] = $farmer->code;
                        unset($transaction[$key]["account_farmer_id"]);
                    }
                    return $transaction;
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

    public function detail(Request $request){
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $response["code"] = 200;
            $response["message"] = "Success";

            $transactionId = $request->input("trx-id");
            $farmerCode = $request->input("farmer-code");

            $cacheName = "detail.purchase_order";
            if($transactionId){
                $cacheName .= ".transaction_id|$transactionId";
            }
            if($farmerCode){
                $cacheName .= ".farmer_code|$farmerCode";
            }
        
            $result = Cache::remember($cacheName, config("constant.ttl"), function() use($transactionId, $farmerCode){
                return PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->join("purchase_order_transaction", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->join("account_farmer", "purchase_order_transaction.account_farmer_id", "account_farmer.id")
                    ->join("t_vcp", "t_vcp.id", "purchase_order_transaction.vcp_id")
                    //->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    //->join("sub_districts", "sub_districts.id", "t_vcp.sub_district_id")
                    //->join("districts", "districts.id", "sub_districts.district_id")
                    //->join("cities", "cities.id", "districts.city_id")
                    //->join("provinces", "provinces.id", "cities.province_id")
                    ->when($transactionId, function($builder) use($transactionId){
                        return $builder->where("purchase_order_transaction.transaction_id", $transactionId);
                    })
                    ->when($farmerCode, function($builder) use($farmerCode){
                        return $builder->where("account_farmer.code", $farmerCode);
                    })
                    ->select(DB::raw("t_evc.code AS evc_code, t_vch.code AS vch_code, t_vcp.code AS vcp_code, accounts.code AS vendor_code, users.name AS vendor, po_number, po_date, expected_shipping_date, item.name AS item_name, item_type.name AS item_type, item_unit.name AS item_unit, item_description, purchase_order.item_quantity, item_unit_price, item_max_quantity, purchase_order.status, purchase_order_transaction.receipt_number, purchase_order_transaction.transaction_date, purchase_order_transaction.floating_rate, purchase_order_transaction.total_item_price"))
                    ->first();
            });
            $result["farmer_code"] = $farmerCode;
            $result["transaction_id"] = $transactionId;
            $result["farmer_name"] = Cache::remember("farmer_name_by_code|$farmerCode", config("constant.ttl"), function() use($farmerCode){
                $farmer = Farmer::findByCode($farmerCode);
                $user = User::findById($farmer->user_id);

                return $user->name;
            });
            $result["pulper_name"] = Cache::remember("pulper_name_by_code|$result->vcp_code", config("constant.ttl"), function() use($result){
                $vcp = VCP::join("account_vcp", "t_vcp.id", "account_vcp.vcp_id")
                ->join("accounts", "accounts.id", "account_vcp.account_id")
                ->join("users", "users.id", "accounts.user_id")
                ->select(DB::raw("users.name"))->first();
                return $vcp->name;
            });
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }

        return view("transactions.purchase-order.transaction-detail", compact("result"));
    }
}
