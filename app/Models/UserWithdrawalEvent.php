<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWithdrawalEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','organization_id','event_id','account_id','bank_name','account_number', 'nominal', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function account()
    {
        return $this->belongsTo('App\Models\BillingAccount', 'account_id');
    }
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
