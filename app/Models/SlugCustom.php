<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlugCustom extends Model
{
    use HasFactory;
    protected $fillable = [
        'event_id', 'slug_custom'
    ];

    public function event()
    {
        return $this->belongsTo('App\Models\Event','event_id');
    }
}
