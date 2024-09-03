<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VCP extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 't_vcp';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("vcp.list_combo", config("constant.ttl"), function(){
            $result = VCP::join("t_vch", "t_vch.id", "t_vcp.vch_id")
            ->join("t_evc", "t_evc.id", "t_vch.evc_id")
            ->select(DB::raw("CONCAT(t_evc.code, '-', t_vch.code, '-', t_vcp.code) AS code"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code']];
            });
        });
    }

    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("vcp.withTrashed.code|$code", config("constant.ttl"), function() use($code){
            return VCP::withTrashed()->where("code", $code)->first();   
        });
    }

    public static function findActiveByCode($code){
        return empty($code) ? null : Cache::remember("vcp.code|$code", config("constant.ttl"), function() use($code){
            return VCP::where("code", $code)->first();   
        });
    }

    public static function getCodeOnly(){
        return Cache::remember("vcp.get_code_only", config("constant.ttl"), function(){
            return VCP::select(DB::raw("code"))->get()->toArray();
        });
    }
}
