<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handbook extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'event_id', 'exhibitor_id', 'file_name', 'slug', 'type_file',
    ];
    public function event(){
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
    public function exhibitor(){
        return $this->belongsTo('App\Models\Exhibitor', 'exhibitor_id');
    }
}
