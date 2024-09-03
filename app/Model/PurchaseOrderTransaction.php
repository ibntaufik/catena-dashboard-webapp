<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PurchaseOrderTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'purchase_order_transaction';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
}
