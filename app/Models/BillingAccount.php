<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'bank_name', 'account_number','access_code','status'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function withdrawals()
    {
        return $this->hasMany('App\Models\UserWithdrawalEvent', 'account_id');
    } 
}
