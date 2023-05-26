<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','type','name','logo','website','priority',
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

}
