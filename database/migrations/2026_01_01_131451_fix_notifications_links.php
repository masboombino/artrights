<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all notifications without links
        $notifications = DB::table('notifications')
            ->whereNotNull('data')
            ->where('data', 'not like', '%"link"%')
            ->get();

        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            if (!$data) continue;

            $type = $data['type'] ?? $notification->type ?? null;
            $user = DB::table('users')->find($notification->user_id);
            if (!$user) continue;

            $link = $this->buildNotificationLink($type, $data, $user);

            if ($link) {
                $data['link'] = $link;
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data, JSON_UNESCAPED_UNICODE)]);
            }
        }
    }

    private function buildNotificationLink($type, $data, $user)
    {
        $email = $user->email ?? '';

        // Artwork notifications
        if (in_array($type, ['artwork_approved', 'artwork_rejected', 'artwork_activated', 'artwork_submitted', 'platform_tax_paid', 'pv_artwork_usage']) && isset($data['artwork_id'])) {
            if (str_contains($email, 'artist')) {
                return route('artist.show-artwork', $data['artwork_id']);
            } elseif (str_contains($email, 'gest')) {
                return route('gestionnaire.show-artwork', $data['artwork_id']);
            } elseif (str_contains($email, 'admin')) {
                return route('admin.dashboard');
            }
        }

        // Wallet notifications
        if (in_array($type, ['pv_funds_released', 'wallet_recharge_approved', 'wallet_recharge_rejected']) ||
            (str_contains($type ?? '', 'wallet') && isset($data['wallet_recharge_id']))) {
            if (str_contains($email, 'artist')) {
                return route('artist.wallet');
            } elseif (str_contains($email, 'gest') && isset($data['wallet_recharge_id'])) {
                return route('gestionnaire.wallet-recharge.show', $data['wallet_recharge_id']);
            } elseif (str_contains($email, 'admin')) {
                return route('admin.dashboard');
            }
        }

        // Complaint notifications
        if (in_array($type, ['complaint_created', 'complaint_answered', 'complaint_resolved', 'complaint_status_updated', 'complaint_taken', 'complaint_in_progress', 'complaint_forwarded', 'complaint_assigned']) && isset($data['complaint_id'])) {
            if (str_contains($email, 'admin')) {
                return route('admin.view-complaint', $data['complaint_id']);
            } elseif (str_contains($email, 'gest')) {
                return route('gestionnaire.complaints.show', $data['complaint_id']);
            } elseif (str_contains($email, 'agent')) {
                return route('agent.complaints.show', $data['complaint_id']);
            } elseif (str_contains($email, 'artist')) {
                return route('artist.complaints.index');
            } elseif (str_contains($email, 'superadmin')) {
                return route('superadmin.complaints.show', $data['complaint_id']);
            }
        }

        // PV notifications
        if (in_array($type, ['pv_opened', 'pv_finalized', 'pv_closed', 'pv_payment_validated', 'pv_agent_confirmed']) && isset($data['pv_id'])) {
            if (str_contains($email, 'gest')) {
                return route('gestionnaire.pvs.show', $data['pv_id']);
            } elseif (str_contains($email, 'agent')) {
                return route('agent.pvs.show', $data['pv_id']);
            }
        }

        // Mission notifications
        if ($type === 'mission_assigned' && isset($data['mission_id'])) {
            if (str_contains($email, 'gest')) {
                return route('gestionnaire.missions.show', $data['mission_id']);
            } elseif (str_contains($email, 'agent')) {
                return route('agent.missions.show', $data['mission_id']);
            } elseif (str_contains($email, 'admin')) {
                return route('admin.dashboard');
            }
        }

        // User notifications
        if ($type === 'agent_created' && isset($data['user_id'])) {
            if (str_contains($email, 'admin')) {
                return route('admin.view-user', $data['user_id']);
            } elseif (str_contains($email, 'gest')) {
                return route('gestionnaire.agents.show', $data['user_id']);
            }
        }

        // Report notifications
        if (in_array($type, ['report_created', 'report_answered', 'admin_complaint']) && isset($data['report_id'])) {
            if (str_contains($email, 'superadmin')) {
                return route('superadmin.reports.show', $data['report_id']);
            } elseif (str_contains($email, 'admin')) {
                return route('admin.reports.show', $data['report_id']);
            }
        }

        // Artist registration
        if (in_array($type, ['artist_registration', 'new_artist']) && isset($data['artist_id'])) {
            if (str_contains($email, 'admin')) {
                return route('admin.view-artist', $data['artist_id']);
            }
        }

        // Super admin response
        if ($type === 'super_admin_response') {
            if (str_contains($email, 'admin')) {
                return route('admin.complaints.sent');
            }
        }

        return null;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
