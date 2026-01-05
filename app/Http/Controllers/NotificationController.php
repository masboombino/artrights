<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        $unreadCount = $user->notifications()
            ->where('is_read', false)
            ->count();

        $notifications = Notification::where('user_id', $user->id)
            ->with('sender')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('blades.notifications.index', [
            'notifications' => $notifications,
            'unreadCountBeforeLoad' => $unreadCount,
        ]);
    }

    public function markAllRead()
    {
        $user = Auth::user();

        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function markRead($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->update([
            'is_read' => true,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    public function delete($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted successfully.');
    }

    public function viewAndMarkRead($id)
    {
        $user = Auth::user();

        $notification = Notification::where('user_id', $user->id)
            ->findOrFail($id);

        // Mark as read if not already read
        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);
        }

        // Get route prefix from user role
        $routePrefix = $this->getRoutePrefixFromUser($user);

        // Get the redirect URL from notification data
        $notificationData = $notification->data ?? [];
        $type = $notificationData['type'] ?? $notification->type ?? null;
        $redirectUrl = null;

        // Build URL based on notification type and data (priority over stored link)
        if (!empty($notificationData)) {

            // Build URL based on type and available data
            if (($type === 'artist_registration' || $type === 'new_artist') && isset($notificationData['artist_id'])) {
                if ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.view-artist', $notificationData['artist_id']);
                } elseif (isset($notificationData['link'])) {
                    $redirectUrl = $notificationData['link'];
                }
            } elseif ($type === 'admin_complaint' && isset($notificationData['complaint_id'])) {
                if ($routePrefix === 'superadmin') {
                    $redirectUrl = route('superadmin.complaints.show', $notificationData['complaint_id']);
                }
            } elseif (in_array($type, ['artwork_approved', 'artwork_rejected', 'artwork_activated', 'artwork_submitted']) && isset($notificationData['artwork_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.show-artwork', $notificationData['artwork_id']);
                } elseif ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.show-artwork', $notificationData['artwork_id']);
                } elseif ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.dashboard');
                }
            } elseif (in_array($type, ['complaint_created', 'complaint_answered', 'complaint_resolved', 'complaint_status_updated', 'complaint_taken', 'complaint_in_progress', 'complaint_forwarded', 'complaint_assigned']) && isset($notificationData['complaint_id'])) {
                if ($routePrefix === 'admin') {
                    return redirect()->route('admin.complaints.show', $notificationData['complaint_id']);
                } elseif ($routePrefix === 'gestionnaire') {
                    return redirect()->route('gestionnaire.complaints.show', $notificationData['complaint_id']);
                } elseif ($routePrefix === 'agent') {
                    return redirect()->route('agent.complaints.show', $notificationData['complaint_id']);
                } elseif ($routePrefix === 'artist') {
                    return redirect()->route('artist.complaints.show', $notificationData['complaint_id']);
                } elseif ($routePrefix === 'superadmin') {
                    return redirect()->route('superadmin.complaints.show', $notificationData['complaint_id']);
                }
            } elseif ((str_contains($type ?? '', 'wallet') || $type === 'wallet_recharge_request') && isset($notificationData['wallet_recharge_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.wallet');
                } elseif ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.wallet-recharge.show', $notificationData['wallet_recharge_id']);
                } elseif ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.dashboard');
                }
            } elseif (str_contains($type ?? '', 'wallet') && $routePrefix === 'artist') {
                $redirectUrl = route('artist.wallet');
            } elseif (in_array($type, ['pv_opened', 'pv_finalized', 'pv_closed', 'pv_payment_validated', 'pv_agent_confirmed']) && isset($notificationData['pv_id'])) {
                if ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.pvs.show', $notificationData['pv_id']);
                } elseif ($routePrefix === 'agent') {
                    $redirectUrl = route('agent.pvs.show', $notificationData['pv_id']);
                }
            } elseif (in_array($type, ['agent_created', 'artist_account_approved']) && isset($notificationData['user_id'])) {
                if ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.view-user', $notificationData['user_id']);
                } elseif ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.agents.show', $notificationData['user_id']);
                }
            } elseif (in_array($type, ['report_created', 'report_answered']) && isset($notificationData['report_id'])) {
                if ($routePrefix === 'superadmin') {
                    $redirectUrl = route('superadmin.reports.show', $notificationData['report_id']);
                } elseif ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.reports.show', $notificationData['report_id']);
                }
            } elseif ($type === 'platform_tax_paid' && isset($notificationData['artwork_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.show-artwork', $notificationData['artwork_id']);
                }
            } elseif ($type === 'pv_artwork_usage' && isset($notificationData['artwork_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.show-artwork', $notificationData['artwork_id']);
                }
            } elseif ($type === 'mission_assigned' && isset($notificationData['mission_id'])) {
                if ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.missions.show', $notificationData['mission_id']);
                } elseif ($routePrefix === 'agent') {
                    $redirectUrl = route('agent.missions.show', $notificationData['mission_id']);
                } elseif ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.dashboard');
                }
            } elseif ($type === 'pv_funds_released' && isset($notificationData['pv_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.wallet');
                }
            } elseif (in_array($type, ['wallet_recharge_approved', 'wallet_recharge_rejected']) && isset($notificationData['wallet_recharge_id'])) {
                if ($routePrefix === 'artist') {
                    $redirectUrl = route('artist.wallet');
                } elseif ($routePrefix === 'gestionnaire') {
                    $redirectUrl = route('gestionnaire.wallet-recharge.show', $notificationData['wallet_recharge_id']);
                }
            } elseif ($type === 'super_admin_response' && isset($notificationData['complaint_id'])) {
                if ($routePrefix === 'admin') {
                    $redirectUrl = route('admin.complaints.sent');
                } elseif ($routePrefix === 'superadmin') {
                    $redirectUrl = route('superadmin.complaints.index');
                }
            }
        }

        // Fallback to stored link if we couldn't build a URL from type
        if (!$redirectUrl && isset($notificationData['link'])) {
            $redirectUrl = $notificationData['link'];
        }

        // Default redirect to notifications page if no URL found
        if (!$redirectUrl) {
            return redirect()->route($routePrefix . '.notifications');
        }

        // Convert absolute URL to relative path if needed
        // This ensures redirect works correctly regardless of APP_URL setting
        if (strpos($redirectUrl, 'http://') === 0 || strpos($redirectUrl, 'https://') === 0) {
            $parsedUrl = parse_url($redirectUrl);
            $redirectUrl = (isset($parsedUrl['path']) ? $parsedUrl['path'] : '/') . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');
        }

        return redirect($redirectUrl);
    }

    /**
     * Get route prefix from user role
     */
    private function getRoutePrefixFromUser($user): string
    {
        if ($user->hasRole('super_admin')) {
            return 'superadmin';
        } elseif ($user->hasRole('admin')) {
            return 'admin';
        } elseif ($user->hasRole('gestionnaire')) {
            return 'gestionnaire';
        } elseif ($user->hasRole('agent')) {
            return 'agent';
        } elseif ($user->hasRole('artist')) {
            return 'artist';
        }

        return 'admin'; // Default fallback
    }
}
