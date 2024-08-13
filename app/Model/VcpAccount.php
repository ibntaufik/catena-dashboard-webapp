<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class VcpAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'account_vcp';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function findByCode($code){
        return empty($code) ? null : Cache::remember("vcp.code|$code", config("constant.ttl"), function() use($code){
            return VCP::where("vcp_code", $code)->first();   
        });
    }
}
