<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'location';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("location.code|$code", config("constant.ttl"), function() use($code){
            return Location::where("code", $code)->first();   
        });
    }

    public static function listCombo(){
        return Cache::remember("listCombo", config("constant.ttl"), function(){
            $result = Location::select(DB::raw("code, CONCAT(code, ' - ', sub_district) AS sub_district"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['code'], "text" => $item['sub_district']];
            }); 
        });
    }
}
