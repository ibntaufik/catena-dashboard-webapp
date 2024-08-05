<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'location';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("location.code|$code", 600, function() use($code){
            return Location::where("code", $code)->first();   
        });
    }
}
