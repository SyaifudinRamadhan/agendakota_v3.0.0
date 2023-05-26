<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePricing extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','description','organizer_count','event_same_time','ticket_commission','session_count',
        'custom_link','sponsor_count','exhibitor_count','partner_media_count',
        'report_download','max_attachment','price','price_in_year','deleted'
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'pkg_id');
    }
}
