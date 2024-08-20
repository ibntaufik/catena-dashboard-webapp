<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VCH extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 't_vch';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("vch.list_combo", config("constant.ttl"), function(){
            $result = VCH::select(DB::raw("code"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['code']];
            });
        });
    }

    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("vch.withTrashed.code|$code", config("constant.ttl"), function() use($code){
            return VCH::withTrashed()->where("code", $code)->first();   
        });
    }

    public static function findActiveByCode($code){
        return empty($code) ? null : Cache::remember("vch.code|$code", config("constant.ttl"), function() use($code){
            return VCH::where("code", $code)->first();   
        });
    }
}
