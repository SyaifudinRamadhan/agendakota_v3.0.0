<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    protected $fillable = [
        'sender','receiver','buy_ticket','response','event_id','ticket_id','purchase_id'
    ];
    use HasFactory;
    public function senders() {
        return $this->belongsTo('App\Models\User', 'sender');
    }
    public function events() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function tickets() {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id');
    }
    public function purchase(){
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }
}
