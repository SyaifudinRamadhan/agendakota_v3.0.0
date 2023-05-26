<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionistEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'user_id',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
