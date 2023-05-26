<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','slug','name','logo','type','interest','description','banner_img','deleted'
    ];

    public function events() {
        return $this->hasMany('App\Models\Event', 'organizer_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
