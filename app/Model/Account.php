<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'accounts';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findById($id){
        return empty($code) ? null : Cache::remember("account|$id", config("constant.ttl"), function() use($id){
            return Account::find($id);
        });
    }
    
    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("account.withTrashed.code|$code", config("constant.ttl"), function() use($code){
            return Account::withTrashed()->where("code", $code)->first();
        });
    }
    
    public static function findActiveByCode($code){
        return empty($code) ? null : Cache::remember("account.code|$code", config("constant.ttl"), function() use($code){
            return Account::where("code", $code)->first();
        });
    }

    public static function listVendor(){
        return Cache::remember("account.list_combo.vendor", config("constant.ttl"), function(){
            $result = Account::join("users", "users.id", "accounts.user_id")->where("accounts.status", "Vendor")->select(DB::raw("code, status, users.name"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code']." - ".$item['name']];
            });
        });
    }

    public static function listFieldCoordinator(){
        return Cache::remember("account.list_combo.fieldCoordinator", config("constant.ttl"), function(){
            $result = Account::join("users", "users.id", "accounts.user_id")->where("accounts.status", "Field Coordinator")->select(DB::raw("code, status, users.name"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code']." - ".$item['name']];
            });
        });
    }
}
