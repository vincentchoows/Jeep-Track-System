<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitRenewal extends Model
{
    protected $table = 'permit_renewals';

    // Define the inverse of the relationship
    public function permitApplication()
    {
        return $this->belongsTo(PermitApplication::class);
    }
}
