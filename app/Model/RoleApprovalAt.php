<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class RoleApprovalAt extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'role_approval_at';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    
}
