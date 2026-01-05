<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletRechargeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'bank_name',
        'account_number',
        'card_number',
        'payment_proof_path',
        'notes',
        'status',
        'approved_by',
        'rejection_reason',
        'approved_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'PENDING';
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'PENDING';
    }
}
