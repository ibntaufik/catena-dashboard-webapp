<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Model\Approval;
use App\Model\Item;
use App\Model\ItemType;
use App\Model\ItemUnit;
use App\Model\PurchaseOrder;
use App\Model\PurchaseOrderApproval;
use App\Model\VCH;
use App\Model\VchAccount;
use App\Helpers\BaseClient;
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

        $page = $request->input("start");
        $limit = $request->input("limit");
        $status = $request->input("status", "all");
        $isVchAdmin = Auth::user()->isA('vch_admin');

        try{
            $cacheName = "purchase_order.status_$status";
            if($isVchAdmin){
                $cacheName .= ".user_vch";
            }

            $vchCode = $request->input("vch_code");
            if($vchCode){
                $cacheName .= ".vch_code|$vchCode";
            }
            
            $vendor = $request->input("vendor");
            if($vendor){
                $cacheName .= ".vendor|$vendor";
            }
            
            $itemQuantity = $request->input("item_quantity");
            if($itemQuantity){
                $cacheName .= ".item_quantity|$itemQuantity";
            }
            
            $poNumber = $request->input("po_number");
            if($poNumber){
                $cacheName .= ".po_number|$poNumber";
            }
            
            $dateRangePo = $request->input("daterange_po");
            $startDatePo = "";
            $endDatePo = "";
            if($dateRangePo){
                $dateRange = explode(" to ",$dateRangePo);
                $startDatePo = DateTime::createFromFormat('d-m-Y', $dateRange[0])->format('Y-m-d');
                $endDatePo = DateTime::createFromFormat('d-m-Y', $dateRange[1])->format('Y-m-d');

                $cacheName .= ".start_date_po|$startDatePo";
                $cacheName .= ".end_date_po|$endDatePo";
            }

            $dateRangeExpectedShipping = $request->input("daterange_expected_shipping");
            $startDateExpectedShipping = "";
            $endDateExpectedShipping = "";
            if($dateRangeExpectedShipping){
                $dateRange = explode(" to ",$dateRangeExpectedShipping);
                $startDateExpectedShipping = DateTime::createFromFormat('d-m-Y', $dateRange[0])->format('Y-m-d');
                $endDateExpectedShipping = DateTime::createFromFormat('d-m-Y', $dateRange[1])->format('Y-m-d');

                $cacheName .= ".start_date_expected_shipping|$startDateExpectedShipping";
                $cacheName .= ".end_date_expected_shipping|$endDateExpectedShipping";
            }
            
            $itemType = $request->input("item_type");
            if($itemType){
                $cacheName .= ".item_type|$itemType";
            }
            
            $itemName = $request->input("item_name");
            if($isVchAdmin){
                $itemName .= ".item_name|$itemName";
            }
            
            $itemDescription = $request->input("item_description");
            if($itemDescription){
                $cacheName .= ".item_description|$itemDescription";
            }
            
            $floatingRate = $request->input("floating_rate");
            if($floatingRate){
                $cacheName .= ".floating_rate|$floatingRate";
            }
            
            $itemPrice = $request->input("item_price");
            if($itemPrice){
                $cacheName .= ".item_price|$itemPrice";
            }

            $itemMaxQuantity = $request->input("item_max_quantity");
            if($itemMaxQuantity){
                $cacheName .= ".item_max_quantity|$itemMaxQuantity";
            }

            if($page){
                $cacheName .= ".page|$page";
            }

            $response["count"] = Cache::remember("count.".$cacheName, config("constant.ttl"), function() use($status, $isVchAdmin, $vchCode, $vendor, $itemQuantity, $poNumber, $startDatePo, $endDatePo, $startDateExpectedShipping, $endDateExpectedShipping, $itemType, $itemName, $itemDescription, $floatingRate, $itemPrice, $itemMaxQuantity){

                return PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when($isVchAdmin, function($builder){
                        return $builder->where("users.id", Auth::user()->id);
                    })
                    ->when(!empty($status) && in_array($status, ["waiting", "approved", "rejected"]), function($builder) use($status){
                        return $builder->where("purchase_order.status", $status);
                    })
                    ->when($vchCode, function($builder) use($vchCode){
                        return $builder->where("t_vch.code", $vchCode);
                    })
                    ->when($vendor, function($builder) use($vendor){
                        return $builder->where("users.name", $vendor);
                    })
                    ->when($itemQuantity, function($builder) use($itemQuantity){
                        return $builder->where("purchase_order.item_quantity", $itemQuantity);
                    })
                    ->when($poNumber, function($builder) use($poNumber){
                        return $builder->where("purchase_order.po_number", $poNumber);
                    })
                    ->when($startDatePo && $endDatePo, function($builder) use($startDatePo, $endDatePo){
                        return $builder->whereRaw("purchase_order.po_date BETWEEN ? AND ?", [$startDatePo, $endDatePo]);
                    })
                    ->when($startDateExpectedShipping && $endDateExpectedShipping, function($builder) use($startDateExpectedShipping, $endDateExpectedShipping){
                        return $builder->whereRaw("purchase_order.expected_shipping_date BETWEEN ? AND ?", [$startDateExpectedShipping, $endDateExpectedShipping]);
                    })
                    ->when($itemType, function($builder) use($itemType){
                        return $builder->where("item_type.name", $itemType);
                    })
                    ->when($itemName, function($builder) use($itemName){
                        return $builder->where("item.name", $itemName);
                    })
                    ->when($itemDescription, function($builder) use($itemDescription){
                        return $builder->where("purchase_order.item_description", $itemDescription);
                    })
                    ->when($itemMaxQuantity, function($builder) use($itemMaxQuantity){
                        return $builder->where("purchase_order.item_max_quantity", $itemMaxQuantity);
                    })
                    ->when($floatingRate, function($builder) use($floatingRate){
                        return $builder->join("purchase_order_transaction", "purchase_order.id", "purchase_order_transaction.purchase_order_id")
                        ->where("purchase_order_transaction.floating_rate", $floatingRate);
                    })
                    ->when($itemPrice, function($builder) use($itemPrice){
                        return $builder->where("purchase_order.item_unit_price", $itemPrice);
                    })
                    ->count();
            });

            $response["data"] = Cache::remember("datalist.".$cacheName, config("constant.ttl"), function() use($status, $isVchAdmin, $vchCode, $vendor, $itemQuantity, $poNumber, $startDatePo, $endDatePo, $startDateExpectedShipping, $endDateExpectedShipping, $itemType, $itemName, $itemDescription, $floatingRate, $itemPrice, $itemMaxQuantity, $page, $limit){

                return PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when($isVchAdmin, function($builder){
                        return $builder->where("users.id", Auth::user()->id);
                    })
                    ->when(!empty($status) && in_array($status, ["waiting", "approved", "rejected"]), function($builder) use($status){
                        return $builder->where("purchase_order.status", $status);
                    })
                    
                    ->when($vchCode, function($builder) use($vchCode){
                        return $builder->where("t_vch.code", $vchCode);
                    })
                    ->when($vendor, function($builder) use($vendor){
                        return $builder->where("users.name", $vendor);
                    })
                    ->when($itemQuantity, function($builder) use($itemQuantity){
                        return $builder->where("purchase_order.item_quantity", $itemQuantity);
                    })
                    ->when($poNumber, function($builder) use($poNumber){
                        return $builder->where("purchase_order.po_number", $poNumber);
                    })
                    ->when($startDatePo && $endDatePo, function($builder) use($startDatePo, $endDatePo){
                        return $builder->whereRaw("purchase_order.po_date BETWEEN ? AND ?", [$startDatePo, $endDatePo]);
                    })
                    ->when($startDateExpectedShipping && $endDateExpectedShipping, function($builder) use($startDateExpectedShipping, $endDateExpectedShipping){
                        return $builder->whereRaw("purchase_order.expected_shipping_date BETWEEN ? AND ?", [$startDateExpectedShipping, $endDateExpectedShipping]);
                    })
                    ->when($itemType, function($builder) use($itemType){
                        return $builder->where("item_type.name", $itemType);
                    })
                    ->when($itemName, function($builder) use($itemName){
                        return $builder->where("item.name", $itemName);
                    })
                    ->when($itemDescription, function($builder) use($itemDescription){
                        return $builder->where("purchase_order.item_description", $itemDescription);
                    })
                    ->when($itemQuantity, function($builder) use($itemQuantity){
                        return $builder->where("purchase_order.item_quantity", $itemQuantity);
                    })
                    ->when($itemMaxQuantity, function($builder) use($itemMaxQuantity){
                        return $builder->where("purchase_order.item_max_quantity", $itemMaxQuantity);
                    })
                    ->when($floatingRate, function($builder) use($floatingRate){
                        return $builder->join("purchase_order_transaction", "purchase_order.id", "purchase_order_transaction.purchase_order_id")
                        ->where("purchase_order_transaction.floating_rate", $floatingRate);
                    })
                    ->when($itemPrice, function($builder) use($itemPrice){
                        return $builder->where("purchase_order.item_unit_price", $itemPrice);
                    })
                    ->when($page, function($builder) use($page){
                        return $builder->skip($page);
                    })
                    ->when($limit, function($builder) use($limit){
                        return $builder->take($limit);
                    })
                    ->select(DB::raw("t_evc.code AS evc_code, t_vch.code AS vch_code, accounts.code AS vendor_code, users.name AS vendor, po_number, po_date, expected_shipping_date, item.name AS item_name, item_type.name AS item_type, item_unit.name AS item_unit, item_description, item_quantity, item_unit_price, item_max_quantity, purchase_order.status"))
                    ->orderBy("purchase_order.created_at", "DESC")
                    ->get();
            });

            if($response["count"] > 0){
                $response["code"] = 200;
                $response["message"] = "Success";
            } else {
                $response["code"] = 404;
                $response["message"] = "Not found";
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
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
            $status = $request->input("status", "approved");

            if(empty($vchCode)){
                $response["message"] = "VCH user cannot be empty.";
            } else {
                $cacheName = "status_$status";

                if(is_array($vchCode) && (count($vchCode) > 0)){
                    $cacheName .= "vch_code_".implode("|", $vchCode).".";
                } else {
                    $response['message'] = "VCH code must be in array";
                    return response()->json($response);
                }
                
                $response["count"] = Cache::remember("count.po.$cacheName", 120, function() use($status, $vchCode){
                    return PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->when(!empty($status) && in_array($status, ["waiting", "approved", "rejected"]), function($builder) use($status){
                        return $builder->where("purchase_order.status", $status);
                    })
                    ->when(count($vchCode) > 0, function($builder) use($vchCode){
                        return $builder->whereIn("t_vch.code", $vchCode);
                    })
                    ->count();
                });

                $response["data"] = Cache::remember("list.po.$cacheName", 120, function() use($status, $vchCode, $page, $limit){
                    $result = PurchaseOrder::join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                    ->join("accounts", "account_vch.account_id", "accounts.id")
                    ->join("users", "accounts.user_id", "users.id")
                    ->join("t_vch", "t_vch.id", "account_vch.vch_id")
                    ->join("t_evc", "t_evc.id", "t_vch.evc_id")
                    ->join("item_type", "item_type.id", "purchase_order.item_type_id")
                    ->join("item", "item.id", "item_type.item_id")
                    ->join("item_unit", "item_unit.id", "purchase_order.item_unit_id")
                    ->leftJoin("purchase_order_transaction", "purchase_order_transaction.purchase_order_id", "purchase_order.id")
                    ->when(!empty($status) && in_array($status, ["waiting", "approved", "rejected"]), function($builder) use($status){
                        return $builder->where("purchase_order.status", $status);
                    })
                    ->when(count($vchCode) > 0, function($builder) use($vchCode){
                        return $builder->whereIn("t_vch.code", $vchCode);
                    })
                    ->when(!empty($page) && !empty($limit), function($builder) use($page, $limit){
                        return $builder->offset($page)->limit($limit);
                    })
                    ->select(DB::raw("t_evc.code 
                        AS evc_code, 
                        t_vch.code AS vch_code, 
                        accounts.code AS vendor_code, 
                        users.name AS vendor, 
                        po_number, 
                        po_date, 
                        expected_shipping_date, 
                        item.name AS item_name, 
                        item_type.name AS item_type, 
                        item_unit.name AS item_unit, 
                        purchase_order.item_description, 
                        purchase_order.item_quantity, 
                        purchase_order.item_unit_price, 
                        purchase_order.item_max_quantity, 
                        purchase_order.status, 
                        SUM(purchase_order_transaction.item_quantity) AS 'weight_fulfilled'"))
                    ->groupBy(DB::raw("t_evc.code, 
                        t_vch.code, 
                        accounts.code, 
                        users.name, 
                        po_number, 
                        po_date, 
                        expected_shipping_date, 
                        item.name, 
                        item_type.name, 
                        item_unit.name, 
                        purchase_order.item_description, 
                        purchase_order.item_quantity, 
                        purchase_order.item_unit_price, 
                        purchase_order.item_max_quantity, 
                        purchase_order.status,
                        purchase_order.created_at"))
                    ->orderBy("purchase_order.created_at", "DESC")
                    ->get();

                    foreach($result as $r){
                        if($r->weight_fulfilled == null){
                            $r->weight_fulfilled = 0;
                        }
                    }

                    return $result;
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

            $input["created_by"] = Auth::user()->name;
            $po = PurchaseOrder::create($input);

            // Get HO Approver
            $approverIds = Approval::join("users", "users.id", "ho_approval.user_id")->select("users.id")
            ->get()->toArray();

            // Get vhc approver
            $vchApprover = VchAccount::join("accounts", "account_vch.account_id", "accounts.id")
            ->where("account_vch.id", $input["account_vch_id"])->select("accounts.user_id")->first();

            $approver = [];
            if(!empty($vchApprover)){
                $approver[] = [
                    "purchase_order_id" => $po->id,
                    "user_id"           => $vchApprover->user_id,
                    "status"            => "waiting",
                    "created_by"        => Auth::user()->name,
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
            }
            foreach ($approverIds as $key => $id) {
                $approver[] = [
                    "purchase_order_id" => $po->id,
                    "user_id"           => $id["id"],
                    "status"            => "waiting",
                    "created_by"        => Auth::user()->name,
                    "updated_at"        => date("Y-m-d H:i:s")
                ];
            }
            
            PurchaseOrderApproval::insert($approver);


            $client = new BaseClient();
            $client->pushNotificationOneSignal("An purchase order ".$input["po_number"]." have been created.");
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

    public function update(Request $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => []
        ];
        
        try{
            $input = $request->except(["_token"]);
            
            $po = PurchaseOrder::where("po_number", $input["po_number"])->first();
            if(empty($po)){
                throw new \Exception("PO Number ".$input["po_number"]." is not registered");
            } else if($po->status != "waiting"){
                throw new \Exception("PO Number ".$input["po_number"]." status is ".$po->status);
            }

            PurchaseOrderApproval::where([
                "purchase_order_id" => $po->id,
                "status" => "waiting",
                'user_id' => Auth::user()->id,
            ])->update([
                "status" => $input['po_status'], 
                'reason_rejected' => $input['po_status'] == "rejected" ? $input['reason'] : null,
            ]);

            if($input['po_status'] == "rejected"){
                PurchaseOrder::where("po_number", $input["po_number"])->update(["status" => "rejected"]);
            } else {
                $checkApproval = PurchaseOrderApproval::where([
                    "purchase_order_id" => $po->id,
                    "status" => "approved",
                ])->select("purchase_order_id", "status")->get();

                $approvers = PurchaseOrderApproval::where([
                    "purchase_order_id" => $po->id,
                ])->select("purchase_order_id", "status")->get();

                if(count($checkApproval) == count($approvers)){
                    PurchaseOrder::where("po_number", $input["po_number"])->update(["status" => "approved"]);

                    $client = new BaseClient();
                    $client->pushNotificationOneSignal("An purchase order ".$input["po_number"]." have been approved.");
                }
            }
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

    public function latestHistory(Request $request){
        
        $response = [
            "code"      => 400,
            "message"   => "Failed to complete request",
            "data"      => [
                "show_approval_buttons" => false,
                "approver" => [
                    "po_number" => "-",
                    "name" => "-",
                    "status" => "-",
                    "created_at" => "-",
                ]
            ]
        ];
        
        try{
            $po = PurchaseOrder::join("purchase_order_approval", "purchase_order.id", "purchase_order_approval.purchase_order_id")
            ->where("po_number", $request->input("po_number"))
            ->when($request->has("po_status") 
                && in_array($request->input("po_status"), ["waiting", "approved", "rejected"]), 
                function($builder) use($request){
                    return $builder->where("purchase_order_approval.status", $request->input("po_status"));
            })->select("reason_rejected", "purchase_order_approval.user_id")->first();

            $listApprover = PurchaseOrder::join("purchase_order_approval", "purchase_order.id", "purchase_order_approval.purchase_order_id")
            ->join("users", "purchase_order_approval.user_id", "users.id")
            ->where("purchase_order.po_number", $request->input("po_number"))
            ->whereNull("purchase_order_approval.deleted_at")
            ->select(DB::raw("po_number, users.name AS text, users.id AS user_id, purchase_order_approval.status, purchase_order_approval.updated_at"))
            ->get();
            
            $response["code"] = 200;
            $response["message"] = "Success";
            $response["data"]["reason"] = !empty($po) ? $po->reason_rejected : null;
            $vchUserDoneApproval = $this->vchUserDoneApproval($request->input("po_number"));
            
            if(!empty($vchUserDoneApproval) && (Auth::user()->id == $vchUserDoneApproval->user_id) && ($vchUserDoneApproval->status == "waiting")){ 
                $response["data"]["show_approval_buttons"] = true;
            } else if((Auth::user()->id != $vchUserDoneApproval->user_id) && $vchUserDoneApproval->status == "approved" && !$this->hasUserDoneApproval($listApprover, Auth::user()->id)){
                $response["data"]["show_approval_buttons"] = true;
            }
             ;
            if(!empty($listApprover)){
                $response["data"]["approver"] = $listApprover;
            }
        } catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    public function item(){
        $response = [
            "code"          => 400,
            "message"       => "Failed to complete request",
            "data"          => [
                "item"      => [],
                "item_type" => []
            ]
        ];
        try{
            $response["code"] = 200;
            $response["message"] = "Success";

            $response["data"]["item"] = Item::list();
            $response["data"]["item_type"] = Item::listType();
        } catch (\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        
        return response()->json($response);
    }

    function vchUserDoneApproval($poNumber){
        $result = null;
        try{
            $result = PurchaseOrder::join("purchase_order_approval", "purchase_order.id", "purchase_order_approval.purchase_order_id")
                ->join("account_vch", "purchase_order.account_vch_id", "account_vch.id")
                ->join("accounts", function($join){
                    return $join->on("account_vch.account_id", "accounts.id")
                    ->on("purchase_order_approval.user_id", "accounts.user_id");
                })
                ->where([
                    "purchase_order.po_number" => $poNumber,
                ])->whereNull("purchase_order_approval.deleted_at")
                ->select(DB::raw("po_number, purchase_order_approval.user_id, purchase_order_approval.status, purchase_order_approval.updated_at"))->first();
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());
        }
        return $result;
    }

    function hasUserDoneApproval($listApprover, $userId){

        $isApproved = false;
        foreach($listApprover as $approver){
            if($approver->user_id == $userId && $approver->status == "approved"){
                $isApproved = true;
                break;
            };
        }

        return $isApproved;
    }
}
