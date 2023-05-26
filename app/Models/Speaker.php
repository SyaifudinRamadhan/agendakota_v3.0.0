<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','name','job','company','email','instagram','linkedin','website','twitter','photo','overview'
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function SessionSpeaker() {
        return $this->hasMany('App\Models\SessionSpeaker', 'speaker_id');
    }




}
