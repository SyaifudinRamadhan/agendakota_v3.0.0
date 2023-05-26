<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCheckin extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','purchase_id','checkin'
    ];

    public function purchases()
    {
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }
}
