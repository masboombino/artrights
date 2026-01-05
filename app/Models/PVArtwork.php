<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PVArtwork extends Model
{
    use HasFactory;

    protected $table = 'pv_artwork';

    protected $fillable = [
        'pv_id',
        'artwork_id',
        'device_id',
        'hours_used',
        'plays_count',
        'base_rate',
        'fine_amount',
        'notes',
    ];

    protected $casts = [
        'hours_used' => 'float',
        'plays_count' => 'integer',
        'base_rate' => 'float',
        'fine_amount' => 'float',
    ];

    public function pv()
    {
        return $this->belongsTo(PV::class, 'pv_id');
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}

