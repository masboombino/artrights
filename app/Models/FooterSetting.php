<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo_path',
        'website_url',
        'ayrade_url',
        'mahdid_anes_url',
        'support_url',
        'help_url',
        'maps_url',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'copyright_text',
        'developer_text',
    ];

    /**
     * Get the default footer settings or create if not exists
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        if (!$settings) {
            $settings = self::create([
                'copyright_text' => '© 2026 ArtRights. All rights reserved to Ayrade company',
                'developer_text' => 'Under development and programming of Mahdid Anes',
                'website_url' => url('/'),
                'support_url' => route('support'),
                'help_url' => route('help'),
            ]);
        }
        
        return $settings;
    }
}
