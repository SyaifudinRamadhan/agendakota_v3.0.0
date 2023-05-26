<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'ticket_id', 'status'
    ];

    public function ticket(){
        return $this->belongsTo('App\Models\Ticket', 'ticket_id');
    }
    public function buyer(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
