<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class District extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'districts';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function listByCityId($id){
        return empty($id) ? [] : Cache::remember("coverage.district.city_id|$id", config("constant.ttl"), function() use($id){
            return District::where("city_id", $id)->select(DB::raw("id, name AS text"))->orderBy("name", "ASC")->get()->toArray();
        });
    }
}
