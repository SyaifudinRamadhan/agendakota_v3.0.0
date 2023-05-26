<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','email','password','token','photo','is_active','pkg_id','pkg_status','created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function organizations() {
        return $this->hasMany('App\Models\Organization', 'user_id')->where('deleted', 0);
    }

    public function organizationsTeam() {
        return $this->hasMany('App\Models\OrganizationTeam', 'user_id');
    }
    public function countInvitation() {
        return $this->hasMany('App\Models\Invitation', 'user_id');
    }
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'user_id');
    }
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'user_id');
    }
    public function purchasesEvent($eventID)
    {
        return $this->hasMany(Purchase::class, 'user_id')->where('event_id',$eventID);
    }
    public function bankAccounts()
    {
        return $this->hasMany('App\Models\BillingAccount', 'user_id');
    }
    public function withdrawals()
    {
        return $this->hasMany('App\Models\UserWithdrawalEvent', 'user_id');
    }
    public function package()
    {
        return $this->belongsTo('App\Models\PackagePricing', 'pkg_id');
    }
    public function packagePayments()
    {
        return $this->hasMany('App\Models\PackagePayment','user_id');
    }
}
