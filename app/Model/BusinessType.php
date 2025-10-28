<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BusinessType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'master_business_type';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listByName($name){
        return Cache::remember("business_type|$name", config("constant.ttl"), function() use($name){
            return BusinessType::when(!empty($name), function($builder) use($name){
                return $builder->whereRaw("UPPER(name) LIKE ?", ["$name%"]);
            })->select(DB::raw("id, name AS text"))->orderBy("name", "ASC")->get()->toArray();
        });
    }
}
