<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'booth_id','name','price','image','url'
    ];

    public function booth() {
        return $this->belongsTo('App\Models\Exhibitor', 'booth_id');
    }
}
