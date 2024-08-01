<?php

namespace App\Models;

use App\Observers\VehicleObserver;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /**
     * Register the observer(s) for the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(VehicleObserver::class);
    }
    protected $fillable = [
        'id',
        'type',
        'reg_no',
        'model',
        'created_at',
        'updated_at',
    ];

    public function permitApplication()
    {
        return $this->belongsTo(PermitApplication::class, 'id', 'vehicle_id');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'type');
    }



}
