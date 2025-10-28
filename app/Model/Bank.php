<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'master_bank';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("bank.withTrashed.code|$code", config("constant.ttl"), function() use($code){
            return Bank::withTrashed()->where("code", $code)->first();   
        });
    }
    
    public static function findActiveByCode($code){
        return empty($code) ? null : Cache::remember("bank.code|$code", config("constant.ttl"), function() use($code){
            return Bank::where("code", $code)->first();   
        });
    }

    public static function listByName($name){
        return Cache::remember("bank_$name", config("constant.ttl"), function() use($name){
            return Bank::when(!empty($name), function($builder) use($name){
                return $builder->whereRaw("UPPER(name) LIKE ?", ["$name%"]);
            })->select(DB::raw("id, name AS text"))->orderBy("name", "ASC")->get()->toArray();
        });
    }
}
