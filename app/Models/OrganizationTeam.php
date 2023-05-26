<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationTeam extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id', 'organization_id', 'role'
    ];

    public function organizations() {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
    }
    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
