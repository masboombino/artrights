<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationship with PVs
    public function pvs()
    {
        return $this->hasMany(PV::class, 'shop_type', 'name');
    }

    // Scope for active shop types
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for shop types by category
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
