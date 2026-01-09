<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PV extends Model
{
    use HasFactory;

    protected $table = 'pv';

    protected $fillable = [
        'agent_id',
        'agency_id',
        'mission_id',
        'shop_name',
        'shop_type',
        'date_of_inspection',
        'status',
        'payment_method',
        'payment_status',
        'agent_payment_confirmed',
        'agent_confirmed_at',
        'file_path',
        'base_rate',
        'total_amount',
        'cash_received_amount',
        'payment_proof_path',
        'closed_at',
        'funds_released_at',
        'finalized_at',
        'finalized_by',
        'notes',
    ];

    protected $casts = [
        'date_of_inspection' => 'datetime',
        'agent_payment_confirmed' => 'boolean',
        'agent_confirmed_at' => 'datetime',
        'closed_at' => 'datetime',
        'funds_released_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'pv_id');
    }

    public function artworkUsages()
    {
        return $this->hasMany(PVArtwork::class, 'pv_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'pv_id');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function isFinalized(): bool
    {
        return $this->finalized_at !== null;
    }

    public function canBeFinalized(): bool
    {
        return $this->status === 'CLOSED' 
            && $this->payment_status === 'VALIDATED' 
            && $this->funds_released_at !== null
            && !$this->isFinalized();
    }

    public function recalculateTotals(): void
    {
        $total = $this->artworkUsages()->sum('fine_amount');
        $this->total_amount = $total;
        $this->saveQuietly();
    }

    public function artistTotals(): array
    {
        $totals = [];
        $this->artworkUsages()->with('artwork.artist')->get()->each(function ($usage) use (&$totals) {
            if (!$usage->artwork || !$usage->artwork->artist) {
                return;
            }
            $artistId = $usage->artwork->artist->id;
            $totals[$artistId] = ($totals[$artistId] ?? 0) + $usage->fine_amount;
        });

        return $totals;
    }

    public function canReleaseFunds(): bool
    {
        return $this->payment_status === 'VALIDATED' && $this->funds_released_at === null && $this->total_amount > 0;
    }

    public function markFundsReleased(): void
    {
        $this->funds_released_at = now();
        $this->save();
    }

    public function canClosePV(): bool
    {
        // Cannot close if already finalized
        if ($this->isFinalized()) {
            return false;
        }

        // Cannot close if already closed
        if ($this->status === 'CLOSED') {
            return false;
        }

        // Payment must be confirmed by agent
        if (!$this->agent_payment_confirmed) {
            return false;
        }

        // Payment amount must match total amount (with small tolerance for floating point)
        $totalAmount = $this->total_amount;
        $receivedAmount = $this->cash_received_amount ?? 0;
        $difference = abs($receivedAmount - $totalAmount);

        if ($difference > 0.01) {
            return false;
        }

        return true;
    }

    public function evidenceFiles(): array
    {
        if (!$this->file_path) {
            return [];
        }

        $decoded = json_decode($this->file_path, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        return [$this->file_path];
    }
}
