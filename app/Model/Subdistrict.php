<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Subdistrict extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'sub_districts';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function listByDistrictId($id){
        return empty($id) ? [] : Cache::remember("coverage.sub_district.district_id|$id", config("constant.ttl"), function() use($id){
            $result = Subdistrict::where("district_id", $id)->select(DB::raw("id, CONCAT(code, ' - ', name) AS text"))->orderBy("name", "ASC")->get()->toArray();
            return $result;
        });
    }
}
