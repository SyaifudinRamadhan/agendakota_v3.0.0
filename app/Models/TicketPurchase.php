<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'send_from', 'event_id', 'ticket_id', 'payment_id', 'code', 'price', 'checked_in'
    ];

    public function event() {
        return $this->belongsTo(\App\Models\Event::class, 'event_id');
    }
    public function ticket() {
        return $this->belongsTo(\App\Models\Ticket::class, 'ticket_id');
    }
    public function buyer() {
        return $this->belongsTo(\App\Models\User::class, 'send_from');
    }
    public function holder() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function payment() {
        return $this->belongsTo(\App\Models\TicketPayment::class, 'payment_id');
    }
}
