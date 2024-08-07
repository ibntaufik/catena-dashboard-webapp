<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Bank extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'bank';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("bank.code|$code", config("constant.ttl"), function() use($code){
            return Bank::where("code", $code)->first();   
        });
    }
}
