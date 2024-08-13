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
        return Cache::remember("vcp.list_combo.vch", config("constant.ttl"), function(){
            $result = VCP::select(DB::raw("code"))->get()->toArray();
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
}
