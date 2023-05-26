<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'user','sender','receiver','event_group','message'
    ];
    
    public function user()
    {
        return $this->hasOne('App\Models\User', 'sender');
    }
}
