<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Approval extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ho_approval';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findById($id){
        return empty($id) ? null : Cache::remember("approval.id|$id", config("constant.ttl"), function() use($id){
            return Approval::find($id);   
        });
    }
    
    public static function list(){
        return Cache::remember("approval.list", config("constant.ttl"), function(){
            return Approval::join("users", "ho_approval.user_id", "users.id")
            ->join("ho_account", "ho_account.user_id", "users.id")
            ->select(DB::raw("ho_approval.id, users.name AS text"))->get()->toArray();   
        });
    }
    
    public static function historyApprover($poNumber = null){
        return Cache::remember("approval.list.po_number_$poNumber", config("constant.ttl"), function() use($poNumber){
            return Approval::join("users", "ho_approval.user_id", "users.id")
            ->join("ho_account", "ho_account.user_id", "users.id")
            ->leftJoin("purchase_order_approval", "users.id", "purchase_order_approval.user_id")
            ->leftJoin("purchase_order", "purchase_order.id", "purchase_order_approval.purchase_order_id")
            ->when(!empty($poNumber), function($builder) use($poNumber){
                return $builder->where("purchase_order.po_number",$poNumber);
            })->whereNull("purchase_order_approval.deleted_at")
            ->select(DB::raw("po_number, users.name AS text, purchase_order_approval.status, purchase_order_approval.created_at"))->get()->toArray();   
        });
    }
}
