<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rundown extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','start_date','end_date','start_time','end_time',
        'duration','name','description','deleted'
    ];

    public function event(){
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function sessions() {
        return $this->hasMany(\App\Models\Session::class, 'start_rundown_id');
    }
    public function sessionspeakers()
    {
        return $this->hasMany('App\Models\SessionSpeaker','rundown_id');
    }
}
