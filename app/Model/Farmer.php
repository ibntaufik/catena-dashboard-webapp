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

    public static function isIdNumberExist($IdNumber){
        $user = Farmer::where("id_number", $IdNumber)->first();
        return empty($user) ? false : true;
    }
}
