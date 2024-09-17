<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class HOAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ho_account';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findById($id){
        return empty($id) ? null : Cache::remember("ho_account.id|$id", config("constant.ttl"), function() use($id){
            return HOAccount::find($id);   
        });
    }
    
    public static function findByUserId($id){
        return empty($id) ? null : Cache::remember("ho_account.user_id|$id", config("constant.ttl"), function() use($id){
            return HOAccount::where("user_id", $id)->first();   
        });
    }
}
