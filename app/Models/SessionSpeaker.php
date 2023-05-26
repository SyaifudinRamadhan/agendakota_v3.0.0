<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'rundown_id','speaker_id','deleted'
    ];

    public function speaker() {
        return $this->belongsTo('App\Models\Speaker', 'speaker_id');
    }
}
