<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LocalityAsset extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'master_locality_assets';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
