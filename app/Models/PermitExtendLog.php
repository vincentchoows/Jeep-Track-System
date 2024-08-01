<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitExtendLog extends Model
{
    protected $table = 'permit_extend_logs';



    public function permitApplication()
    {
        return $this->belongsTo(PermitApplication::class,'id');
    }

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user');
    }
}
