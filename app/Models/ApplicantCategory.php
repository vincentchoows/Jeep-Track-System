<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantCategory extends Model
{
    protected $table = 'applicant_categories';


    public function permitApplications()
    {
        return $this->hasMany(PermitApplication::class, 'id');
        
    }





}
