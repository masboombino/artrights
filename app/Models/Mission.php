<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'gestionnaire_id',
        'agent_id',
        'complaint_id',
        'title',
        'description',
        'location_text',
        'map_link',
        'latitude',
        'longitude',
        'scheduled_at',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function pv()
    {
        return $this->hasOne(PV::class);
    }

    public function complaint()
    {
        return $this->belongsTo(Complain::class);
    }
}

