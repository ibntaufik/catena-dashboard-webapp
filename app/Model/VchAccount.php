<?php

namespace App\Model;

use App\Model\VCH;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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

        $isVchAdmin = Auth::user()->isA('vch_admin');
        $cacheName = "account.combo.vch";
        if($isVchAdmin){
            $cacheName .= "user_vch";
        }
        
        return Cache::remember($cacheName, config("constant.ttl"), function() use($isVchAdmin){

            $vch = VchAccount::join("accounts", "account_vch.account_id", "accounts.id")
                ->join("t_vch", "account_vch.vch_id", "t_vch.id")
                ->join("t_evc", "t_vch.evc_id", "t_evc.id")
                ->join("users", "accounts.user_id", "users.id")
                ->when($isVchAdmin, function($builder){
                    return $builder->where("users.id", Auth::user()->id);
                })->select(DB::raw("t_vch.id, CONCAT(t_evc.code, '-', t_vch.code) AS text"))->groupBy(DB::raw("t_vch.id, t_evc.code, t_vch.code"))
                ->get()->toArray();

            foreach ($vch as $key => $row) {
                $vchAccount = VchAccount::join("accounts", "account_vch.account_id", "accounts.id")
                ->join("t_vch", "account_vch.vch_id", "t_vch.id")
                ->join("users", "accounts.user_id", "users.id")
                ->when($isVchAdmin, function($builder){
                    return $builder->where("users.id", Auth::user()->id);
                })
                ->where("account_vch.vch_id", $row["id"])
                ->select(DB::raw("account_vch.id, CONCAT('(', accounts.code, ') ', users.name) AS text"))
                ->get()->toArray();
                
                $vch[$key]["vendor"] =  array_merge([
                    ['id' => 'select', 'text' => '-- Select --', 'disabled' => true, "selected" => true],
                ], $vchAccount);
            }

            return $vch;
        });
    }
}
