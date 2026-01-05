<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'pv_id',
        'artist_id',
        'artwork_id',
        'type',
        'amount',
        'payment_method',
        'payment_status',
        'description',
    ];

    public function pv()
    {
        return $this->belongsTo(PV::class, 'pv_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function artwork()
    {
        return $this->belongsTo(Artwork::class);
    }
}
