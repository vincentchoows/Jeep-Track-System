<?php

namespace App\Observers;

use App\Models\Vehicle;
use App\Models\PermitApplicationLog;
use App\Models\PermitApplication;
use OpenAdmin\Admin\Facades\Admin;
use Illuminate\Support\Facades\Auth;
class VehicleObserver
{
    /**
     * Handle the Vehicle "created" event.
     */

    protected $trackedVehicles = [
        'type', 
        'reg_no', 
        'model', 
    ];

    public function created(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "updated" event.
     */
    public function updated(Vehicle $vehicle): void
    {
        if ($vehicle->wasRecentlyCreated) {
            return;
        }

        $adminUser = Admin::user() ?? Auth::user();

        //When customer edits a permit (vehicle)
        if ($adminUser->is(Auth::user()) && ($vehicle->isDirty()) && $vehicle->permitApplication->status == 1) {
            $vehicle->permitApplication->status = 0;
            $vehicle->permitApplication->saveQuietly();
            return;
        }

        //Changelog
        $this->logVehicleChanges($vehicle, $adminUser);
    }

    /**
     * Handle the Vehicle "deleted" event.
     */
    public function deleted(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "restored" event.
     */
    public function restored(Vehicle $vehicle): void
    {
        //
    }

    /**
     * Handle the Vehicle "force deleted" event.
     */
    public function forceDeleted(Vehicle $vehicle): void
    {
        //
    }

    //======================================================================
    //======================================================================

    protected function logVehicleChanges(Vehicle $vehicle, $adminUser = null): void
    {
        if(!($adminUser && $adminUser == Admin::user())){
            return;
        }

        // Track changes in vehicles fields
        $vehiclesChanged = false;
        foreach ($this->trackedVehicles as $field) {
            if ($vehicle->isDirty($field)) {
                $vehiclesChanged = true;
                break;
            }
        }

        if ($vehiclesChanged) {
            // Log the change for general info fields
            $log = new PermitApplicationLog();
            $adminUser = Admin::user();
            $log->admin_user_id = $adminUser->id;
            $log->permit_application_id = $vehicle->permitApplication->id;
            $log->description = "Updated vehicle details.";
            $log->save();
        }
    }
}
