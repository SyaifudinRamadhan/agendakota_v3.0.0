<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoothHandout extends Model
{
    use HasFactory;

    protected $fillable = [
        'booth_id','type','content'
    ];
}
