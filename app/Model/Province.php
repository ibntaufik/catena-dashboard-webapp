<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Province extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'provinces';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listByName($name){
        return Cache::remember("coverage.province_$name", config("constant.ttl"), function() use($name){
            return Province::when(!empty($name), function($builder) use($name){
                return $builder->whereRaw("UPPER(name) LIKE ?", ["$name%"]);
            })->select(DB::raw("id, name AS text"))->orderBy("name", "ASC")->get()->toArray();
        });
    }

    public static function findBySubdistrictId($subdistrictId){
        return Cache::remember("coverage.province.sub_district_id|$subdistrictId", config("constant.ttl"), function() use($subdistrictId){
            return Province::join("cities", "cities.province_id", "provinces.id")
                ->join("districts", "districts.city_id", "cities.id")
                ->join("sub_districts", "sub_districts.district_id", "districts.id")
                ->where("sub_districts.id", $subdistrictId)
                ->select(DB::raw("provinces.id, provinces.name, provinces.code"))
                ->first();
            });
    }
}
