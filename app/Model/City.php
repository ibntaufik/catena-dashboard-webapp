<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class City extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'cities';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function listByProvinceId($id){
        return empty($id) ? null : Cache::remember("coverage.cities.province_id|$id", config("constant.ttl"), function() use($id){
            return City::where("province_id", $id)->select(DB::raw("id, name AS text"))->orderBy("name", "ASC")->get()->toArray();
        });
    }
}
