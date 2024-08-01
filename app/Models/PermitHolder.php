<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermitHolder extends Model
{
    protected $table = 'permit_holder';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'name',
        'identification_no',
        'contact_no',
        // Add more attributes as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function permitApplication()
    {
        return $this->hasMany(PermitApplication::class, 'id');
        
    }


    // Custom accessor to get the user's name
    public function getUserNameAttribute()
    {
        return $this->user->name;
    }


}
