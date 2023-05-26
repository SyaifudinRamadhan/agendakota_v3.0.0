<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','token_trx','pay_state','order_id','price',
    ];

    public function purchases(){
        return $this->hasMany('App\Models\Purchase', 'payment_id');
    }
    public function tempShares(){
        return $this->hasMany('App\Models\TempTicketShare', 'payment_id');
    }
}
