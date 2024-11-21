<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Evc extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 't_evc';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("evc.list_combo", config("constant.ttl"), function(){
            $result = Evc::select(DB::raw("code"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code']];
            });
        });
    }

    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("evc.code|$code", config("constant.ttl"), function() use($code){
            return Evc::withTrashed()->where("code", $code)->first();   
        });
    }

    public static function isExist($code, $provinceId){
        return empty($code) ? null : Cache::remember("evc.code_$code|provinceid_$provinceId", config("constant.ttl"), function() use($code, $provinceId){
            return Evc::join("provinces", "provinces.evc_id", "t_evc.id")->withTrashed()->where([
                "t_evc.code" => $code,
                "provinces.id" => $provinceId
            ])->first();   
        });
    }
}
