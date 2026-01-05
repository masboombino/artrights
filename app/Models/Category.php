<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'coefficient',
        'exploitation_rate',
    ];

    protected $casts = [
        'coefficient' => 'float',
        'exploitation_rate' => 'float',
    ];

    public function artworks()
    {
        return $this->hasMany(Artwork::class);
    }
}
