<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','name','priority','icon'
    ];
}
