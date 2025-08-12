<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CoffeeVarietyFarmDetail extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'coffee_variety_farm_detail';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
