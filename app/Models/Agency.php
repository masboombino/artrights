<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_name',
        'wilaya',
        'admin_id',
        'bank_account_number',
    ];

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function pvs()
    {
        return $this->hasMany(PV::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function artists()
    {
        return $this->hasMany(Artist::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }

    public function wallet()
    {
        return $this->hasOne(AgencyWallet::class);
    }

    public function gestionnaires()
    {
        return $this->hasMany(User::class, 'agency_id')
            ->whereHas('roles', function($q) {
                $q->where('name', 'gestionnaire');
            });
    }
}
