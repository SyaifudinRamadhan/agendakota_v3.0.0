<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','domain','template',
        'site_title','tagline','meta_description','meta_keyword'
    ];
}
