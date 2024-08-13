<?php

namespace App\model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class VchAccount extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'account_vch';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function listCombo(){
        return Cache::remember("account.list_combo.vch", config("constant.ttl"), function(){
            $result = VCH::select(DB::raw("vch_code"))->get()->toArray();
            return collect($result)->map(function ($item) {
                return ["id" => $item['vch_code'], "text" => $item['vch_code']];
            });
        });
    }
}
