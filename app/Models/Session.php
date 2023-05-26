<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','start_rundown_id','end_rundown_id','title',
        'start_date','end_date','start_time','end_time','description',
        'link','overview','deleted'
    ];

    // public function speakers() {
    //     return $this->hasMany('App\Models\Speaker', 'session_id');
    // }
    public function rundownStart() 
    {
        return $this->belongsTo('App\Models\Rundown', 'start_rundown_id');
    }
    public function rundownEnd()
    {
        return $this->belongsTo('App\Models\Rundown', 'end_rundown_id');
    }
    public function tickets() {
        return $this->hasMany('App\Models\Ticket', 'session_id');
    }
    public function cheapestTicket() {
        return $this->hasMany('App\Models\Ticket', 'session_id')->orderBy('price', 'ASC');
    }
    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function vipLounge(){
        return $this->hasMany('App\Models\VipLoungeEvent', 'session_id');
    }
    // public function purchases($sessions)
    // {
    //     $purchases = [];
    //     $tickets = Ticket::where('session_id',$sessions->id)->get();
    //     for($i=0; $i<count($tickets); $i++){
    //         array_push($purchases, $tickets[$i]->purchase);
    //     }
    //     return $purchases;
    // }
}
