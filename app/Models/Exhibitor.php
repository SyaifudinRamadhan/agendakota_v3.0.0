<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id', 'name', 'email', 'category', 'address', 'instagram',
        'linkedin'
            ,'twitter' 
            ,'website','virtual_booth' 
            ,'booth_link' 
            ,'logo'
            ,'booth_image'
            ,'phone','description'
            ,'video', 'overview',
            'twn_url',
    ];

    public function event() {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function products(){
        return $this->hasMany('App\Models\BoothProduct', 'booth_id');
    }
    public function handbooks(){
        return $this->hasMany('App\Models\Handbook', 'exhibitor_id');
    }
}
