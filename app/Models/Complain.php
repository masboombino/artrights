<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;

    // Type constants
    public const TYPE_COMPLAINT = 'COMPLAINT';
    public const TYPE_REPORT = 'REPORT';

    // Complaint type constants
    public const TYPE_ARTIST_TO_ADMIN = 'ARTIST_TO_ADMIN';
    public const TYPE_ARTIST_TO_GESTIONNAIRE = 'ARTIST_TO_GESTIONNAIRE';
    public const TYPE_ADMIN_TO_SUPERADMIN = 'ADMIN_TO_SUPERADMIN';
    public const TYPE_SUPERADMIN_TO_ADMIN = 'SUPERADMIN_TO_ADMIN';
    public const TYPE_ADMIN_TO_GESTIONNAIRE = 'ADMIN_TO_GESTIONNAIRE';
    public const TYPE_ADMIN_TO_AGENT = 'ADMIN_TO_AGENT';
    public const TYPE_GESTIONNAIRE_TO_ADMIN = 'GESTIONNAIRE_TO_ADMIN';
    public const TYPE_GESTIONNAIRE_TO_AGENT = 'GESTIONNAIRE_TO_AGENT';
    public const TYPE_AGENT_TO_ADMIN = 'AGENT_TO_ADMIN';
    public const TYPE_AGENT_TO_GESTIONNAIRE = 'AGENT_TO_GESTIONNAIRE';

    protected $table = 'complaints';

    protected $fillable = [
        'type',
        'complaint_type',
        'admin_id',
        'super_admin_id',
        'gestionnaire_id',
        'mission_id',
        'agency_id',
        'artist_id',
        'agent_id',
        'sender_user_id',
        'sender_role',
        'target_role',
        'target_user_id',
        'subject',
        'message',
        'location_link',
        'images',
        'files',
        'admin_response',
        'admin_response_images',
        'gestionnaire_response',
        'gestionnaire_response_images',
        'agent_response',
        'agent_response_images',
        'super_admin_response',
        'super_admin_response_images',
        'responded_at',
        'status',
        'hidden_by_users',
    ];

    protected $casts = [
        'images' => 'array',
        'files' => 'array',
        'admin_response_images' => 'array',
        'super_admin_response_images' => 'array',
        'gestionnaire_response_images' => 'array',
        'agent_response_images' => 'array',
        'responded_at' => 'datetime',
        'hidden_by_users' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function mission()
    {
        return $this->belongsTo(Mission::class);
    }

    public function agentProfile()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function superAdmin()
    {
        return $this->belongsTo(User::class, 'super_admin_id');
    }

    public static function resolveType(string $senderRole, string $targetRole): ?string
    {
        // Handle special case: gestionnaire to super_admin
        if ($senderRole === 'gestionnaire' && $targetRole === 'super_admin') {
            return 'GESTIONNAIRE_TO_SUPERADMIN';
        }
        
        return config("complaints.targets.{$senderRole}.{$targetRole}");
    }

    // Scopes
    public function scopeComplaints($query)
    {
        return $query->where('type', self::TYPE_COMPLAINT);
    }

    public function scopeReports($query)
    {
        return $query->where('type', self::TYPE_REPORT);
    }

    public function scopeInbox($query, $userRole, $userId = null, $agencyId = null)
    {
        $query->where('target_role', $userRole);
        
        if ($userId) {
            $query->where(function($q) use ($userId, $agencyId) {
                $q->where('target_user_id', $userId);
                if ($agencyId) {
                    $q->orWhere('agency_id', $agencyId);
                }
            });
        } elseif ($agencyId) {
            $query->where('agency_id', $agencyId);
        }
        
        return $query;
    }

    public function scopeSent($query, $userId)
    {
        return $query->where('sender_user_id', $userId);
    }

    public function scopeNotHiddenBy($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->whereNull('hidden_by_users')
              ->orWhereJsonDoesntContain('hidden_by_users', $userId);
        });
    }

    // Helper methods
    public function isComplaint(): bool
    {
        return $this->type === self::TYPE_COMPLAINT;
    }

    public function isReport(): bool
    {
        return $this->type === self::TYPE_REPORT;
    }

    public function hideForUser($userId): void
    {
        $hiddenBy = $this->hidden_by_users ?? [];
        if (!in_array($userId, $hiddenBy)) {
            $hiddenBy[] = $userId;
            $this->hidden_by_users = $hiddenBy;
            $this->save();
        }
    }

    public function isHiddenForUser($userId): bool
    {
        $hiddenBy = $this->hidden_by_users ?? [];
        return in_array($userId, $hiddenBy);
    }
}
