<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artwork extends Model
{
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'category_id',
        'title',
        'description',
        'file_path',
        'status',
        'rejection_reason',
        'platform_tax_status',
        'platform_tax_amount',
        'platform_tax_paid_at',
    ];

    protected $casts = [
        'platform_tax_amount' => 'float',
        'platform_tax_paid_at' => 'datetime',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function pvUsages()
    {
        return $this->hasMany(PVArtwork::class);
    }

    /**
     * Scope to get only artworks that are available for use in PVs
     * (approved and platform tax paid)
     */
    public function scopeAvailableForPV($query)
    {
        return $query->where('status', 'APPROVED')
                     ->where('platform_tax_status', 'PAID');
    }

    /**
     * Calculate platform tax amount based on category
     * 
     * @param Category|int|null $category Category model or category ID
     * @return float Platform tax amount
     */
    public static function calculatePlatformTax($category)
    {
        // If category is ID, load the category
        if (is_int($category)) {
            $category = Category::find($category);
        }

        if (!$category) {
            return 100; // Default
        }

        $categoryName = strtolower($category->name);

        // Video categories: 500 DZD
        $videoCategories = [
            'short film',
            'movie scene',
            'animation',
            'documentary clip',
            'video art',
        ];

        // Image categories: 200 DZD
        $imageCategories = [
            'painting',
            'drawing',
            'digital art',
            'photography',
            'illustrations',
            'graphic design',
        ];

        // Music categories: 100 DZD
        $musicCategories = [
            'music track',
            'sound effect',
            'song',
            'beat',
            'podcast episode',
            'audiobook',
        ];

        if (in_array($categoryName, $videoCategories)) {
            return 500;
        } elseif (in_array($categoryName, $imageCategories)) {
            return 200;
        } elseif (in_array($categoryName, $musicCategories)) {
            return 100;
        }

        // Default: 100 DZD
        return 100;
    }
}
