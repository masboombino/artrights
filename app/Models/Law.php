<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Law extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'title',
        'notice',
        'sections',
    ];

    protected $casts = [
        'sections' => 'array',
    ];
}
