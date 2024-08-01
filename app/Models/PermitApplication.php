<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Observers\PermitApplicationObserver;

class PermitApplication extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(PermitApplicationObserver::class);
    }

    protected $table = 'permit_application';
    protected $fillable = [
        'serial_no',
        'customer_id',
        'status', 
        'disabled', 
        'renewal_status', 
        'permit_charge_id',
        'feedback',
        'holder_id',
        'company_name',
        'company_address',
        'purpose',
        'applicant_category_id',
        'vehicle_id',
        'surat_permohonan',
        'surat_permohonan_status', 
        'surat_permohonan_comment',
        'surat_indemnity',
        'surat_indemnity_status', 
        'surat_indemnity_comment',
        'salinan_kad_pengenalan',
        'salinan_kad_pengenalan_status', 
        'salinan_kad_pengenalan_comment',
        'salinan_lesen_memandu',
        'salinan_lesen_memandu_status', 
        'salinan_lesen_memandu_comment',
        'salinan_geran_kenderaan',
        'salinan_geran_kenderaan_status', 
        'salinan_geran_kenderaan_comment',
        'salinan_insurans_kenderaan',
        'salinan_insurans_kenderaan_status', 
        'salinan_insurans_kenderaan_comment',
        'salinan_road_tax',
        'salinan_road_tax_status', 
        'salinan_road_tax_comment',
        'gambar_kenderaan',
        'gambar_kenderaan_status', 
        'gambar_kenderaan_comment',
        'surat_sokongan',
        'surat_sokongan_status', 
        'surat_sokongan_comment',
        'documents_change_status', 
        'phc_check', 
        'phc_check_date',
        'phc_check_id',
        'phc_approve', 
        'phc_approve_date',
        'phc_approve_id',
        'phc_second_approve', 
        'phc_second_approve_date',
        'phc_second_approve_id',
        'jkr_check', 
        'jkr_check_date',
        'jkr_check_id',
        'jkr_approve', 
        'jkr_approve_date',
        'jkr_approve_id',
        'finance_check', 
        'finance_check_date',
        'finance_check_id',
        'finance_approve',
        'finance_approve_date',
        'finance_approve_id',
        'transaction_id',
        'transaction_status',
        'permit_renewal_id',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'file' =>'array',
        'surat_permohonan' => 'array', 
        'surat_indemnity' => 'array', 
        'surat_sokongan' => 'array', 
        'salinan_kad_pengenalan' => 'array', 
        'salinan_lesen_memandu' => 'array', 
        'salinan_geran_kenderaan' => 'array', 
        'salinan_insurans_kenderaan' => 'array', 
        'salinan_road_tax' => 'array', 
        'gambar_kenderaan' => 'array', 
        'other_attachment' => 'array', 
    ];

    protected $commentColumns = [
        'surat_permohonan_comment',
        'surat_indemnity_comment',
        'salinan_kad_pengenalan_comment',
        'salinan_lesen_memandu_comment',
        'salinan_geran_kenderaan_comment',
        'salinan_insurans_kenderaan_comment',
        'salinan_road_tax_comment',
        'gambar_kenderaan_comment',
        'surat_sokongan_comment',
    ];


    public function getPhcCheckStatusAttribute()
    {
        return $this->attributes['reviewer']['phc_check']['status'] ?? null;
    }

    public function getPhcCheckCheckDateAttribute()
    {
        return $this->attributes['reviewer']['phc_check']['check_date'] ?? null;
    }

    public function setPhcCheckStatusAttribute($value)
    {
        $reviewer = $this->attributes['reviewer'] ?? [];
        $reviewer['phc_check']['status'] = $value;
        $this->attributes['reviewer'] = $reviewer;
    }




    //Json retriving end  ---------------------



    public function statusUpdatedBy()
    {
        return $this->belongsTo(User::class, 'status_updated_by');
    }

    public function holder()
    {
        return $this->belongsTo(PermitHolder::class, 'holder_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function applicantcat()
    {
        return $this->belongsTo(ApplicantCategory::class, 'applicant_category_id', 'id');
    }

    //-----------------------------------------------------------------------
    //NOTE: DONT USE CAMEL CASING 
    //---------------------------------------------------------------------

    // public function applicantcat()
    // {
    //     return $this->hasOne(ApplicantCategory::class, 'id', 'applicant_category_id');
    // }

    public function phc_checked_by()
    {
        return $this->belongsTo(AdminUser::class, 'phc_check_id');
    }

    public function phc_approved_by()
    {
        return $this->belongsTo(AdminUser::class, 'phc_approve_id');
    }

    public function phc_second_approved_by()
    {
        return $this->belongsTo(AdminUser::class, 'phc_check_id');
    }

    public function jkr_checked_by()
    {
        return $this->belongsTo(AdminUser::class, 'jkr_check_id');
    }

    public function jkr_approved_by()
    {
        return $this->belongsTo(AdminUser::class, 'jkr_approve_id');
    }

    public function finance_checked_by()
    {
        return $this->belongsTo(AdminUser::class, 'finance_check_id');
    }

    public function finance_approved_by()
    {
        return $this->belongsTo(AdminUser::class, 'finance_approve_id');
    }


    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function permitExtendLog()
    {
        return $this->hasMany(PermitExtendLog::class, 'id', 'permit_application_id');
    }

    // Define the relationship
    public function permitRenewals()
    {
        return $this->hasMany(PermitRenewal::class);
    }

    /**
     * Set all comment columns to null.
     *
     * @return void
     */
    public function clearAllComments()
    {
        // Iterate over each comment column and set to null
        foreach ($this->commentColumns as $column) {
            $this->{$column} = null;
        }
    }

}
