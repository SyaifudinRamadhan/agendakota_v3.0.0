<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'external_id', 'payment_link', 'total', 'grand_total',
        'status', 'has_withdrawn'
    ];
}
