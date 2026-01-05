<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'balance',
        'last_transaction',
    ];

    protected $casts = [
        'balance' => 'float',
        'last_transaction' => 'datetime',
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function transactions()
    {
        return $this->hasMany(AgencyWalletTransaction::class);
    }
}

