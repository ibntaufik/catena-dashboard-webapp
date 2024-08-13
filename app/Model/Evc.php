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
        return Cache::remember("account.list_combo.evc", config("constant.ttl"), function(){
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
}
