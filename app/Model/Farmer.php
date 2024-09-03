<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Farmer extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'account_farmer';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function isIdNumberExist($idNumber){
        $user = Cache::remember("farmer.id_number|$idNumber", config("constant.ttl"), function(){
            return Farmer::where("id_number", $idNumber)->first();
        }); 
        return empty($user) ? false : true;
    }

    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("farmer.code|$code", config("constant.ttl"), function() use($code){
            return Farmer::where("code", $code)->first();
        }); 
    }
}
