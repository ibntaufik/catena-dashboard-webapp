<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VchAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'account_vch';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("account.list_combo.vch", config("constant.ttl"), function(){
            $result = VchAccount::join("t_vch", "account_vch.vch_id", "t_vch.id")
            ->join("accounts", "account_vch.account_id", "accounts.id")
            //::join("users", "accounts.user_id", "users.id")
            ->where("status", config("constant.account_status.vendor"))
            ->select(DB::raw("t_vch.code, accounts.code AS vendor"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code'], "name" => $item["vendor"]];
            });
        });
    }

    public static function combo(){
        return Cache::remember("account.combo.vch", config("constant.ttl"), function(){
            $result = VchAccount::join("t_vch", "account_vch.vch_id", "t_vch.id")
            ->join("accounts", "account_vch.account_id", "accounts.id")
            ->join("users", "accounts.user_id", "users.id")
            ->where("status", config("constant.account_status.vendor"))
            ->select(DB::raw("t_vch.code, account_vch.id, CONCAT('(', accounts.code, ') ', users.name) AS vendor"))
            ->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['id'], "text" => $item['code'], "name" => $item["vendor"]];
            });
        });
    }
}
