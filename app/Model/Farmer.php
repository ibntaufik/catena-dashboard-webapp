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
    protected $table = 'farmer_account';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
