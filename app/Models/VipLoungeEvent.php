<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipLoungeEvent extends Model
{
    use HasFactory;
    protected $fillable = [
        'session_id', 'table_count', 'chair_table'
    ];
}
