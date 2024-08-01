<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model 
{
    use Notifiable;
    protected $table = 'admin_users';

    public function permitApplicationLogs()
    {
        return $this->hasMany(PermitApplicationLog::class, 'id');
    }

    public function permitRenewalLogs()
    {
        return $this->hasMany(PermitRenewalLog::class, 'id');
    }
}
