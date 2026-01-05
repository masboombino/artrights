<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'balance',
        'last_transaction',
    ];

    protected $casts = [
        'balance' => 'float',
        'last_transaction' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }
}
