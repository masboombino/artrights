<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyWalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_wallet_id',
        'pv_id',
        'direction',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function wallet()
    {
        return $this->belongsTo(AgencyWallet::class, 'agency_wallet_id');
    }

    public function pv()
    {
        return $this->belongsTo(PV::class, 'pv_id');
    }
}

