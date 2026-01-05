<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'agency_id',
        'stage_name',
        'wallet_id',
        'address',
        'birth_date',
        'birth_place',
        'identity_document',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complain::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
