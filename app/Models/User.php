<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, SoftDeletes, HasRoles;

    protected $fillable = [
        'role_id',
        'agency_id',
        'name',
        'email',
        'password',
        'phone',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class);
    }

    public function artist()
    {
        return $this->hasOne(Artist::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function getProfilePhotoUrlAttribute(): ?string
    {
        if (!$this->profile_photo_path) {
            return null;
        }

        // Clean the path - remove any leading/trailing slashes
        $cleanPath = ltrim($this->profile_photo_path, '/');
        
        // Always check if we're in an API context
        // Check multiple ways to detect API requests
        $isApiRequest = false;
        
        if (request()) {
            $isApiRequest = request()->is('api/*') 
                || request()->expectsJson() 
                || str_starts_with(request()->path(), 'api/')
                || request()->routeIs('api.*')
                || request()->header('Accept') === 'application/json'
                || request()->wantsJson();
        }
        
        // If no request context (e.g., in console/queue), default to API format
        // This is important for API responses where the accessor is called
        if (!$isApiRequest && !request()) {
            $isApiRequest = true;
        }
        
        // For API requests (including when called from API controllers),
        // return relative path: /api/media/profile_photos/xxxxx.jpg
        // Flutter will build the full URL using AuthService.baseUrl
        if ($isApiRequest) {
            return '/api/media/' . $cleanPath;
        }
        
        // For web requests, return full URL using route helper
        try {
            return route('media.show', ['path' => $cleanPath]);
        } catch (\Exception $e) {
            // Fallback to API format if route doesn't exist
            return '/api/media/' . $cleanPath;
        }
    }
}
