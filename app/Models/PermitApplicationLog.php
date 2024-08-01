<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitApplicationLog extends Model
{
    protected $table = 'permit_application_log';

    public function adminUser()
    {
        return $this->belongsTo(AdminUser::class, 'admin_user_id');
    }
}
