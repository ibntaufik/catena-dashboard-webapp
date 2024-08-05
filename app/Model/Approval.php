<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Approval extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'ho_approval';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
    public static function findById($id){
        return empty($id) ? null : Cache::remember("approval.id|$id", 600, function() use($id){
            return Location::find($id);   
        });
    }
}
