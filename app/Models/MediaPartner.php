<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPartner extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id','type','name','logo','website'
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
