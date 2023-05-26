<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','pkg_id','order_id','token_trx','nominal','status'
    ];

    public function package()
    {
        return $this->belongsTo('App\Models\PackagePricing','pkg_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
}
