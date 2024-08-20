<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ItemType extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'item_type';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("item_type.list_combo", config("constant.ttl"), function(){
            return ItemType::select(DB::raw("id, name AS text"))->get()->toArray();
        });
    }
}
