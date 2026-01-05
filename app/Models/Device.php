<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'pv_id',
        'device_type_id',
        'name',
        'type',
        'coefficient',
        'quantity',
        'amount',
        'notes',
    ];

    protected $casts = [
        'coefficient' => 'float',
        'amount' => 'float',
        'quantity' => 'integer',
    ];

    public function pv()
    {
        return $this->belongsTo(PV::class, 'pv_id');
    }

    public function deviceType()
    {
        return $this->belongsTo(DeviceType::class);
    }

    public function usages()
    {
        return $this->hasMany(PVArtwork::class, 'device_id');
    }
}
