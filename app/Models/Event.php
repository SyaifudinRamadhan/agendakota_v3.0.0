<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id','slug','name','category','type','topics','logo','description', 'snk','execution_type','location','province','city','start_date','end_date','start_time','end_time','is_publish','has_withdrawn','punchline','breakdown','instagram','twitter','website','deleted','unique_code'
    ];

    public function organizer() {
        return $this->belongsTo('App\Models\Organization', 'organizer_id');
    }

    public function sessions() {
        return $this->hasMany('App\Models\Session', 'event_id');
    }
    public function sponsors() {
        return $this->hasMany('App\Models\Sponsor', 'event_id');
    }
    public function media_partners()
    {
        return $this->hasMany('App\Models\MediaPartner', 'event_id');
    }
    public function speakers() {
        return $this->hasMany('App\Models\Speaker', 'event_id');
    }
    public function tickets() {
        return $this->hasMany('App\Models\Ticket', 'event_id');
    }
    public function exhibitors(){
        return $this->hasMany('App\Models\Exhibitor', 'event_id');
    }
    public function handouts(){
        return $this->hasMany('App\Models\EventHandout', 'event_id');
    }

    public function purchase()
    {
        return $this->hasMany('App\Models\Purchase', 'event_id');
    }
    public function handbooks(){
        return $this->hasMany('App\Models\Handbook', 'event_id');
    }
    public function receptionists(){
        return $this->hasMany('App\Models\ReceptionistEvent', 'event_id');
    }
    public function rundowns()
    {
        return $this->hasMany('App\Models\Rundown', 'event_id');
    }
    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'event_group');
    }

    public function certificate() {
        return $this->hasOne('App\Models\Certificate', 'event_id');
    }
    public function site() {
        return $this->hasOne('App\Models\EventSite', 'event_id');
    }
}
