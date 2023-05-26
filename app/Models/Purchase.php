<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','send_from','event_id','ticket_id','cart_id','payment_id','quantity','price',
        'code'
    ];

    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function checkin() {
        return $this->hasOne('App\Models\UserCheckin', 'purchase_id');
    }

    public function fromUser(){
        return $this->belongsTo('App\Models\User', 'send_from');
    }

    public function events() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function tickets() {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id');
    }

    public function payment() {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function paymentPaid()
    {
        return $this->belongsTo(Payment::class, 'payment_id')->where('pay_state','Terbayar');
    }
    public function tempFor(){
        return $this->hasOne('App\Models\TempTicketShare', 'purchase_id');
    }
    public function invitation(){
        return $this->hasOne('App\Models\Invitation', 'purchase_id');
    }
}
