<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Farms extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'farms';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
