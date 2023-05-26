<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','session_id','name'
    ];

    public function session() {
        return $this->belongsTo('App\Models\Session', 'session_id');
    }
    public function questions() {
        return $this->hasMany('App\Models\QuizQuestion', 'quiz_id');
    }
}
