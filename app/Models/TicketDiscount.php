<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id', 'name', 'code',
        'discount_type', 'discount_amount',
        'start_date', 'end_date', 'minimum_transaction', 'quantity'
    ];
}
