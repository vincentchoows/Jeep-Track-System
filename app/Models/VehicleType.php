<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $table = 'vehicle_types';


    protected $cast = [];

    protected $guarded = [];

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class, 'id');
    }
}
