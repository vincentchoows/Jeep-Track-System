<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitRenewalLog extends Model
{
    protected $table = 'permit_renewal_logs';

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}
