<?php

namespace App\Console\Commands\Fabric;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\Fabric\FarmerMSP\CreateAsset;
use App\Model\Farmer;
use App\Model\PurchaseOrderTransaction;

class FarmerCreateAsset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farmer:create_asset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create farmer asset into fabric';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $transaction = PurchaseOrderTransaction::join("account_farmer", "account_farmer.id", "purchase_order_transaction.account_farmer_id")
        ->join("users", "users.id", "account_farmer.user_id")
        ->join("sub_districts", "sub_districts.id", "account_farmer.sub_district_id")
        ->join("purchase_order", "purchase_order.id", "purchase_order_transaction.purchase_order_id")
        ->join("item_type", "item_type.id", "purchase_order.item_type_id")
        ->where("purchase_order_transaction.status", "created")
        ->select(DB::raw("purchase_order_transaction.transaction_id, purchase_order_transaction.transaction_date, purchase_order_transaction.receipt_number, purchase_order_transaction.floating_rate, purchase_order_transaction.total_item_price, account_farmer.code AS farmer_code, users.name AS farmer_name, account_farmer.id_number AS farmer_nik, sub_districts.name AS sub_district, sub_districts.latitude, sub_districts.longitude, item_type.name AS item_type_name, purchase_order.po_number, purchase_order.item_description, purchase_order.po_date, purchase_order.expected_shipping_date, purchase_order.item_quantity, purchase_order.item_unit_price, purchase_order.item_max_quantity"))
        ->get();

        $transactionIds = [];
        foreach($transaction as $row){
            if(!empty($row)){
                $asset = array (
                  'assetID' => $row->transaction_id,
                  'farmerId' => $row->farmer_code,
                  'location' => $row->sub_district,
                  'latitude' => $row->latitude,
                  'longitude' => $row->longitude,
                  'itemType' => $row->item_type_name,
                  'description' => $row->item_description,
                  'transactionDate' => $row->transaction_date,
                  'receiptNumber' => $row->receipt_number,
                  'farmerName' => $row->farmer_name,
                  'farmerNik' =>  $row->farmer_nik,
                  'poNumber' => $row->po_number,
                  'poDate' => $row->po_date,
                  'expectedShippingDate' => $row->expected_shipping_date,
                  'itemQuantity' => (double) $row->item_quantity,
                  'itemUnitPrice' => (double) $row->item_unit_price,
                  'floatingRate' => (float) $row->floating_rate,
                  'itemMaxQuantity' => (double) $row->item_max_quantity,
                  'totalPrice' => (double) $row->total_item_price,
                );

                CreateAsset::prosess(json_encode($asset));

                $transactionIds[] = $row->transaction_id;
            }
        }

        if(count($transactionIds) > 0){
            PurchaseOrderTransaction::whereIn("transaction_id", $transactionIds)->update(["status" => "on_process"]);
        }
    }
}
