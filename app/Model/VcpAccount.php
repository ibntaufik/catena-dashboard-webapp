<?php

namespace App\Model;

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

    public static function findById($id){
        return empty($code) ? null : Cache::remember("vcp_account.id|$id", config("constant.ttl"), function() use($id){
            return VcpAccount::find($id);   
        });
    }
}
