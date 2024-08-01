<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected static function boot()
    {
        parent::boot();
    }

    protected $table = 'transactions';
    public function holder()
    {
        return $this->belongsTo(PermitHolder::class, 'customer_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'customer_id');
    }








}
