<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'purchase_order';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function findByPoNumber($poNumber){
        return empty($poNumber) ? null : Cache::remember("purchase_order.po_number|$poNumber", config("constant.ttl"), function() use($poNumber){
            return PurchaseOrder::withTrashed()->where("po_number", $poNumber)->first();   
        });
    }
}
