<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempTicketShare extends Model
{
    use HasFactory;

    protected $fillable = [
        "payment_id","purchase_id","share_to",
    ];

    public function payment(){
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
    public function purchase(){
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }

}
