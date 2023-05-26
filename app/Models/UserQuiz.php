<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuiz extends Model
{
    use HasFactory;
    protected $fillable = [
        'quiz_id','user_id','quiz_scor'
    ];

    public function quiz()
    {
        return $this->belongsTo('App\Models\Quiz', 'quiz_id');
    }
    public function user()
    {
       return $this->belongsTo('App\Models\User', 'user_id');
    }
}
