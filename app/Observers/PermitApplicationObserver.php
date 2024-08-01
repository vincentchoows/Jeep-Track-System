<?php

namespace App\Observers;

use App\Mail\ApprovalChainMailController;
use App\Models\PermitApplication;
use App\Models\PermitHolder;
use App\Models\Vehicle;
use App\Models\PermitApplicationLog;
// use Encore\Admin\Facades\Admin;
use App\Notifications\ApprovalChainNotification;
use App\Notifications\PermitApprovedNotification;
use App\Notifications\SuccessfulTransactionNotification;
use App\Notifications\TransactionNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use OpenAdmin\Admin\Auth\Database\Administrator;
use OpenAdmin\Admin\Facades\Admin;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use OpenAdmin\Admin\Auth\Database\Role;


class PermitApplicationObserver
{

    protected $roles = [
        'phc_check',
        'phc_approve',
        'phc_second_approve',
        'jkr_check',
        'jkr_approve',
        'transaction_status',
        'finance_check',
        'finance_approve',
    ];

    protected $rolesArray = [
        'phc-checker' => 'phc_check',
        'phc-approver' => 'phc_approve',
        'phc-second-approver' => 'phc_second_approve',
        'jkr-checker' => 'jkr_check',
        'jkr-approver' => 'jkr_approve',
        'fin-checker' => 'finance_check',
        'fin-approver' => 'finance_approve',
    ];

    /**
     * Handle the PermitApplication "created" event.
     */
    public function created(PermitApplication $permitApplication): void
    {
        //Send notif to phc_checker first
        $nextRole = 'phc_check';
        $nextRole = array_search($nextRole, $this->rolesArray);
        $nextRoleId = Role::where('slug', $nextRole)->first()->id;
        $roleIds = DB::table(config('admin.database.role_users_table'))
            ->where('role_id', $nextRoleId)
            ->pluck('user_id');
        $allAdminUsers = Administrator::whereIn('id', $roleIds)->get();

        //Send email notif to next all next role users 
        foreach ($allAdminUsers as $user) {
            $user->notify(new ApprovalChainNotification($permitApplication, $user));
        }

        //record in log
        $log = new PermitApplicationLog();
        $adminUser = Admin::user() ?? Auth::user();
        $log->admin_user_id = $adminUser->id;
        $log->permit_application_id = $permitApplication->id;
        $log->description = "A new permit application record has been created.";
        $log->saveQuietly();
    }

    /**
     * Handle the PermitApplication "updated" event.
     */

    public function updated(PermitApplication $permitApplication): void
    {
        //If the application is new
        if ($permitApplication->wasRecentlyCreated) {
            return;
        }

        $adminUser = Admin::user() ?? Auth::user();

        //------------------------------------------------------------------------------------------------------
        // Customer Update Cases
        //------------------------------------------------------------------------------------------------------

        if ($adminUser->is(Auth::user())) {

            // When customer edits a permit
            if ($permitApplication->isDirty() && $permitApplication->status == 1) {
                $this->handleCustomerEdit($permitApplication, $adminUser);

                //Call log method
                //Save changes 
                $permitApplication->saveQuietly();
                return;
            }

            // When customer completes transactions
            if ($permitApplication->isDirty('transaction_status') && $permitApplication->transaction_status == 1) {
                $user = $permitApplication->user;

                //Send email successful to customer 
                $user->notify(new SuccessfulTransactionNotification($permitApplication));

                //Send email notif to next role
                $this->notifyNextRoleUsers('transaction_status', $permitApplication);

                //Call log method
                //Save changes 
                $permitApplication->saveQuietly();
                return;
            }
        }

        //------------------------------------------------------------------------------------------------------
        // Admin Update Cases
        //------------------------------------------------------------------------------------------------------

        if ($adminUser == Admin::user()) {
            $user = $permitApplication->user;

            //Reset approval flow
            $this->resetRejectedStatus($permitApplication);

            //Notify customer for transaction
            if ($permitApplication->isDirty('jkr_approve') && $permitApplication->jkr_approve == 2) {

                $permitApplication->status = 2;
                $user->notify(new TransactionNotification($permitApplication));

            }elseif($permitApplication->isDirty('finance_approve') && $permitApplication->finance_approve == 2){

                //notify customer card activated
                $user->notify(new PermitApprovedNotification($permitApplication));
                $permitApplication->status = 4;
                
            }else {
                //Notify next approval role
                foreach ($this->roles as $role) {
                    if ($role == 'fin-approver') {
                        break;
                    }
                    if ($permitApplication->isDirty($role) && $permitApplication->$role == 2) {
                        if (!(Admin::user()->inRoles(['administrator']))) {
                            $this->notifyNextRoleUsers($role, $permitApplication);
                        }
                    }
                }
            }




        }


        //When PHC 

        $permitApplication->saveQuietly();
        return;

        //When PHC second approver approve
        // if ($permitApplication->isDirty('phc_approve') && $permitApplication->phc_approve == 2) {

        // }


        //When JKR checker approve


        //When JRK approver approve





        //When transactions completed



        //When FIN checker approve

        //When FIN checker approve





        //Changelog (incomplete)
        // if ($adminUser->is(Admin::user())) {
        //     $this->logChanges($permitApplication, $adminUser);
        // }



    }

    /**
     * Handle the PermitApplication "deleted" event.
     */
    public function deleted(PermitApplication $permitApplication): void
    {
        $log = new PermitApplicationLog();
        $adminUser = Admin::user();
        $log->admin_user_id = $adminUser->id;
        $log->permit_application_id = $permitApplication->id;
        $log->description = "A permit application record has been deleted.";
        $log->saveQuietly();
    }


    /**
     * Handle the PermitApplication "restored" event.
     */
    public function restored(PermitApplication $permitApplication): void
    {
        //
    }

    /**
     * Handle the PermitApplication "force deleted" event.
     */
    public function forceDeleted(PermitApplication $permitApplication): void
    {
        //
    }

    //==============================================================================================================
    // Custom Methods
    //==============================================================================================================


    protected function getNextKey($roles, $currentKey)
    {
        // $keys = array_keys($roles);
        $currentIndex = array_search($currentKey, $roles);

        if ($currentIndex === false || $currentIndex === count($roles) - 1) {
            // Key not found or no next key exists
            return null;
        }
        return $currentIndex + 1;
    }

    protected function notifyNextRoleUsers($currentKey, $permitApplication)
    {
        // Get the next role
        $nextKey = $this->getNextKey($this->roles, $currentKey);
        $nextRole = $this->roles[$nextKey];
        $nextRole = array_search($nextRole, $this->rolesArray);

        // Get the role id
        $nextRoleId = Role::where('slug', $nextRole)->first()->id;

        // Get user IDs of the roles attached
        $roleIds = DB::table(config('admin.database.role_users_table'))
            ->where('role_id', $nextRoleId)
            ->pluck('user_id');

        // Get all user class of role attached
        $allAdminUsers = Administrator::whereIn('id', $roleIds)->get();

        // Send email notification to all users in the next role
        foreach ($allAdminUsers as $user) {
            $user->notify(new ApprovalChainNotification($permitApplication, $user));
        }

    }

    /**
     * Check and reset rejected statuses.
     */
    protected function resetRejectedStatus(PermitApplication $permitApplication): void
    {
        $trackedColumns = [
            'phc_check',
            'phc_approve',
            'phc_second_approve',
            'jkr_check',
            'jkr_approve',
        ];

        // Check if any of the tracked columns are dirty and have a value of 1
        $isRejected = false;
        foreach ($trackedColumns as $column) {
            if ($permitApplication->isDirty($column) && $permitApplication->{$column} == 1) {
                $isRejected = true;
                break;
            }
        }

        // Return if no rejections is made
        if ($isRejected == false) {
            return;
        }
        // Change status when rejections occurs 
        else {
            foreach ($trackedColumns as $column) {
                $permitApplication->{$column} = 0;
            }
            $permitApplication->status = 1;

            //Call log method
            //Save db
            $permitApplication->saveQuietly();
        }
    }


    /**
     * 
     * Handle Financial Approval (Final Approval Chain)
     * -> add end_date 
     */

    protected function handleFinalFinanceApproval(PermitApplication $permitApplication): void
    {
        if ($permitApplication->isDirty('jkr_approve') && $permitApplication->jkr_approve == 2) {
            $permitApplication->status = 2;
        } elseif ($permitApplication->isDirty('finance_approve') && $permitApplication->finance_approve == 2) {
            $permitApplication->status = 4;
            if ($permitApplication->end_date === null) {
                $permitApplication->end_date = Carbon::now()->addYear();
            }
        }
        return;
    }


    /**
     * 
     * Handle JKR Approval
     */
    protected function handleFinalJKRApproval(PermitApplication $permitApplication): void
    {

        // dd('id=' .$permitApplication->id, $permitApplication->isDirty('jkr_approve'), $permitApplication->jkr_approve);

        if ($permitApplication->isDirty('jkr_approve') && $permitApplication->jkr_approve == 2) {
            // dd('here');
            // Update status to 2 (Activated)
            $permitApplication->status = 2;
        }

        return;
    }

    /**
     * Log changes for the PermitApplication model.
     */
    protected function logChanges(PermitApplication $permitApplication, $adminUser): void
    {
        $trackedFields = [
            'statuses' => [
                'phc_check',
                'phc_approve',
                'phc_second_approve',
                'jkr_check',
                'jkr_approve',
                'finance_check',
                'finance_approve',
                'transaction_status',
            ],
            'generalInfo' => [
                'customer_id',
                'holder_id',
                'purpose',
                'applicant_category_id',
            ],
            'documents' => [
                'surat_indemnity',
                'salinan_kad_pengenalan',
                'salinan_lesen_memandu',
                'salinan_geran_memandu',
                'salinan_insurans_memandu',
                'salinan_road_tax',
                'surat_sokongan',
                'gambar_kenderaan',
            ],
            'dates' => [
                'end_date'
            ],
        ];

        foreach ($trackedFields as $category => $fields) {
            foreach ($fields as $field) {
                if ($permitApplication->isDirty($field)) {
                    $this->createLogEntry($permitApplication, $adminUser, $field, $category);
                    break; // Only need to log once per category
                }
            }
        }
    }

    /**
     * Create a log entry for a changed field.
     */
    protected function createLogEntry(PermitApplication $permitApplication, $adminUser, $field, $category): void
    {
        $originalValue = $permitApplication->getOriginal($field);
        $updatedValue = $permitApplication->{$field};

        $description = '';

        switch ($category) {
            case 'statuses':
                $prevStatus = $originalValue ? "Approved" : "Pending";
                $postStatus = $updatedValue ? "Approved" : "Pending";
                $formattedField = ucwords(str_replace("_", " ", $field));
                $description = "Changed $formattedField from $prevStatus to $postStatus.";
                break;
            case 'generalInfo':
                $description = "Updated general info.";
                break;
            case 'documents':
                $description = "Updated documents details.";
                break;
            case 'dates':
                $description = "Updated the validation duration of the permit.";
                break;
        }

        $log = new PermitApplicationLog();
        $log->admin_user_id = $adminUser->id;
        $log->permit_application_id = $permitApplication->id;
        $log->description = $description;
        $log->saveQuietly();
    }

    public function handleCustomerEdit(PermitApplication $permitApplication, $adminUser)
    {
        $fileStatusFields = [
            'surat_permohonan' => 'surat_permohonan_status',
            'surat_indemnity' => 'surat_indemnity_status',
            'surat_sokongan' => 'surat_sokongan_status',
            'salinan_kad_pengenalan' => 'salinan_kad_pengenalan_status',
            'salinan_lesen_memandu' => 'salinan_lesen_memandu_status',
            'salinan_geran_kenderaan' => 'salinan_geran_kenderaan_status',
            'salinan_insurans_kenderaan' => 'salinan_insurans_kenderaan_status',
            'salinan_road_tax' => 'salinan_road_tax_status',
            'gambar_kenderaan' => 'gambar_kenderaan_status',
        ];

        // Detect what field is changed and match the corresponding field to its status fields and change to 2
        $dirtyFields = $permitApplication->getDirty();

        foreach ($dirtyFields as $field => $newValue) {
            if (array_key_exists($field, $fileStatusFields)) {
                $statusField = $fileStatusFields[$field];
                $permitApplication->{$statusField} = 2;
            }
        }
        $permitApplication->status = 0;

        // Save and exit
        $permitApplication->feedback_status = 1;
    }



}
