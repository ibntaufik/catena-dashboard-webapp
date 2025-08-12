<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SupplierCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'supplier_category';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
