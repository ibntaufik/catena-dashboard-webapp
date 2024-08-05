<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class VCH extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'vch_account';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
