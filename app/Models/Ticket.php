<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id','name','description','type_price','price','quantity','start_date','end_date','deleted'
// <<<<<<< HEAD
//         'session_id','name','description','type_price','price','quantity','start_date','end_date','deleted'
// =======
//         'session_id','end_session_id','name','description','type_price','price','quantity','start_date','end_date'
// >>>>>>> 6f7b0fd977e062702b5f7dfc26a9663f09ed6aa1
    ];
    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function session() {
        return $this->belongsTo('App\Models\Session', 'session_id');
    }
    // public function end_session(){
    //     return $this->belongsTo('App\Models\Session', 'end_session_id');
    // }
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'ticket_id');
    }
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase', 'ticket_id');
    }
    public function cart(){
        return $this->hasMany('App\Models\TicketCart', 'ticket_id');
    }
}
