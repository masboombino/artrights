@php
    $routePrefix = explode('.', request()->route()->getName())[0];
@endphp

<x-allthepages-layout pageTitle="Notifications">
    <style>
        .notification-card {
            border-radius: 0.75rem;
            padding: 1rem;
            padding-right: 1rem;
            margin-bottom: 0.75rem;
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .notification-card.unread {
            background-color: #F3EBDD;
            animation: glow 2s ease-in-out infinite alternate;
            box-shadow: 0 0 10px rgba(25, 57, 72, 0.3);
        }
        
        .notification-card.read {
            background-color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        @keyframes glow {
            from {
                box-shadow: 0 0 10px rgba(25, 57, 72, 0.3);
            }
            to {
                box-shadow: 0 0 20px rgba(25, 57, 72, 0.6);
            }
        }
        
        @keyframes redGlow {
            from {
                box-shadow: 0 0 2px rgba(231, 98, 104, 0.2);
            }
            to {
                box-shadow: 0 0 4px rgba(231, 98, 104, 0.3);
            }
        }
        
        @keyframes greenGlow {
            from {
                box-shadow: 0 0 2px rgba(16, 185, 129, 0.2);
            }
            to {
                box-shadow: 0 0 4px rgba(16, 185, 129, 0.3);
            }
        }
        
        .notification-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.5rem;
        }
        
        .notification-card-icon.complaint {
            background-color: #FFE5B4;
        }
        
        .notification-card-icon.artwork {
            background-color: #E0E0E0;
        }
        
        .notification-card-icon.wallet {
            background-color: #B8E6D3;
        }
        
        .notification-card-icon.approved {
            background-color: #C8E6C9;
        }
        
        .notification-card-icon.general {
            background-color: #D6BFBF;
        }
        
        .notification-content {
            flex: 1;
            min-width: 0;
            padding-right: 0.5rem;
            padding-bottom: 0.5rem;
        }
        
        .notification-title {
            color: #193948;
            font-size: 1rem;
            font-weight: 700;
            margin: 0 0 0.25rem 0;
        }
        
        .notification-message {
            color: #193948;
            font-size: 0.875rem;
            line-height: 1.4;
            margin: 0 0 0.5rem 0;
            opacity: 0.9;
        }
        
        .notification-time {
            color: #193948;
            font-size: 0.75rem;
            opacity: 0.6;
            margin: 0;
        }
        
        .notification-close {
            position: absolute;
            top: 0;
            right: 0;
            background: rgba(231, 98, 104, 0.18);
            border: none;
            color: #c74a52;
            font-size: 1.75rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0.5rem;
            line-height: 1;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 0.75rem 0 0.5rem;
            transition: all 0.2s;
            animation: redGlow 2s ease-in-out infinite alternate;
            z-index: 10;
        }
        
        .notification-close:hover {
            background-color: rgba(231, 98, 104, 0.35);
            transform: scale(1.05);
        }
        
        .notification-mark-read {
            position: absolute;
            bottom: 0;
            right: 0;
            background: rgba(16, 185, 129, 0.18);
            border: none;
            color: #047857;
            font-size: 0.875rem;
            font-weight: 700;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            line-height: 1;
            min-width: 120px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem 0 0.75rem 0;
            transition: all 0.2s;
            animation: greenGlow 2s ease-in-out infinite alternate;
            z-index: 10;
            white-space: nowrap;
        }
        
        .notification-mark-read:hover {
            background-color: rgba(16, 185, 129, 0.35);
            transform: scale(1.05);
        }
        
        .mark-all-read-btn {
            background-color: #193948;
            color: #F3EBDD;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1.5rem auto 0;
            transition: all 0.3s ease;
        }
        
        .mark-all-read-btn:hover {
            background-color: #2a4d5f;
            transform: translateY(-2px);
        }
        
        @media (max-width: 640px) {
            .notification-card {
                padding: 0.875rem;
                gap: 0.75rem;
            }
            
            .notification-card-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }
            
            .notification-title {
                font-size: 0.95rem;
            }
            
            .notification-message {
                font-size: 0.8rem;
            }

            .notification-close,
            .notification-mark-read {
                min-width: 110px;
                height: 36px;
                padding: 0.4rem 0.6rem;
                font-size: 0.8rem;
            }

            .notification-close {
                font-size: 1.5rem;
            }

            .notification-mark-read {
                font-size: 1.1rem;
            }
        }
    </style>
    
    <div style="padding: 1rem; max-width: 800px; margin: 0 auto;">
        @if(session('success'))
            <div style="background-color: #d1fae5; border: 2px solid #10b981; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; color: #065f46; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif

        @php
            $unreadCount = $notifications->where('is_read', false)->count();
        @endphp

        @forelse($notifications as $notification)
            @php
                $notificationData = $notification->data ?? [];
                $notificationType = $notification->type ?? 'general';

                // First priority: use the link directly stored in notification data
                $link = $notificationData['link'] ?? null;

                // Second priority: build link based on notification type and data
                if (!$link && !empty($notificationData)) {
                    if (($notificationType === 'artist_registration' || $notificationType === 'new_artist') && isset($notificationData['artist_id']) && $routePrefix === 'admin') {
                        $link = route('admin.view-artist', $notificationData['artist_id']);
                    } elseif ($notificationType === 'admin_complaint' && isset($notificationData['complaint_id']) && $routePrefix === 'superadmin') {
                        $link = route('superadmin.view-admin-complaint', $notificationData['complaint_id']);
                    } elseif (($notificationType === 'artwork_approved' || $notificationType === 'artwork_rejected' || $notificationType === 'artwork_activated' || $notificationType === 'artwork_submitted') && isset($notificationData['artwork_id'])) {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.show-artwork', $notificationData['artwork_id']);
                        } elseif ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.show-artwork', $notificationData['artwork_id']);
                        } elseif ($routePrefix === 'admin') {
                            $link = route('admin.dashboard'); // Admin doesn't have individual artwork view
                        }
                    } elseif (($notificationType === 'complaint_created' || $notificationType === 'complaint_answered' || $notificationType === 'complaint_resolved' || $notificationType === 'complaint_status_updated' || $notificationType === 'complaint_taken' || $notificationType === 'complaint_in_progress') && isset($notificationData['complaint_id'])) {
                        if ($routePrefix === 'admin') {
                            $link = route('admin.view-complaint', $notificationData['complaint_id']);
                        } elseif ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.complaints.show', $notificationData['complaint_id']);
                        } elseif ($routePrefix === 'agent') {
                            $link = route('agent.complaints.show', $notificationData['complaint_id']);
                        } elseif ($routePrefix === 'artist') {
                            $link = route('artist.complaints.index');
                        } elseif ($routePrefix === 'superadmin') {
                            $link = route('superadmin.view-admin-complaint', $notificationData['complaint_id']);
                        }
                    } elseif (str_contains($notificationType ?? '', 'wallet') || $notificationType === 'wallet_recharge_request') {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.wallet');
                        } elseif ($routePrefix === 'gestionnaire' && isset($notificationData['wallet_recharge_id'])) {
                            $link = route('gestionnaire.wallet-recharge.show', $notificationData['wallet_recharge_id']);
                        } elseif ($routePrefix === 'admin') {
                            $link = route('admin.dashboard'); // Admin doesn't have wallet recharge view
                        }
                    } elseif (($notificationType === 'pv_opened' || $notificationType === 'pv_finalized') && isset($notificationData['pv_id'])) {
                        if ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.pvs.show', $notificationData['pv_id']);
                        } elseif ($routePrefix === 'agent') {
                            $link = route('agent.pvs.show', $notificationData['pv_id']);
                        }
                    } elseif (($notificationType === 'agent_created' || $notificationType === 'artist_account_approved') && isset($notificationData['user_id'])) {
                        if ($routePrefix === 'admin') {
                            $link = route('admin.view-user', $notificationData['user_id']);
                        } elseif ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.agents.show', $notificationData['user_id']);
                        }
                    } elseif (($notificationType === 'report_created' || $notificationType === 'report_answered') && isset($notificationData['report_id'])) {
                        if ($routePrefix === 'superadmin') {
                            $link = route('superadmin.view-admin-report', $notificationData['report_id']);
                        } elseif ($routePrefix === 'admin') {
                            $link = route('admin.view-report', $notificationData['report_id']);
                        }
                    } elseif ($notificationType === 'platform_tax_paid' && isset($notificationData['artwork_id'])) {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.show-artwork', $notificationData['artwork_id']);
                        }
                    } elseif ($notificationType === 'pv_artwork_usage' && isset($notificationData['artwork_id'])) {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.show-artwork', $notificationData['artwork_id']);
                        }
                    } elseif ($notificationType === 'pv_agent_confirmed' && isset($notificationData['pv_id'])) {
                        if ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.pvs.show', $notificationData['pv_id']);
                        }
                    } elseif ($notificationType === 'mission_assigned' && isset($notificationData['mission_id'])) {
                        if ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.missions.show', $notificationData['mission_id']);
                        } elseif ($routePrefix === 'agent') {
                            $link = route('agent.missions.show', $notificationData['mission_id']);
                        } elseif ($routePrefix === 'admin') {
                            $link = route('admin.dashboard');
                        }
                    } elseif (($notificationType === 'pv_closed' || $notificationType === 'pv_payment_validated') && isset($notificationData['pv_id'])) {
                        if ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.pvs.show', $notificationData['pv_id']);
                        } elseif ($routePrefix === 'agent') {
                            $link = route('agent.pvs.show', $notificationData['pv_id']);
                        }
                    } elseif ($notificationType === 'pv_funds_released' && isset($notificationData['pv_id'])) {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.wallet');
                        }
                    } elseif (($notificationType === 'complaint_forwarded' || $notificationType === 'complaint_assigned') && isset($notificationData['complaint_id'])) {
                        if ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.complaints.show', $notificationData['complaint_id']);
                        } elseif ($routePrefix === 'admin') {
                            $link = route('admin.complaints.show', $notificationData['complaint_id']);
                        }
                    } elseif ($notificationType === 'super_admin_response' && isset($notificationData['complaint_id'])) {
                        if ($routePrefix === 'admin') {
                            $link = route('admin.complaints.sent');
                        } elseif ($routePrefix === 'superadmin') {
                            $link = route('superadmin.complaints.index');
                        }
                    } elseif (($notificationType === 'wallet_recharge_approved' || $notificationType === 'wallet_recharge_rejected') && isset($notificationData['wallet_recharge_id'])) {
                        if ($routePrefix === 'artist') {
                            $link = route('artist.wallet');
                        } elseif ($routePrefix === 'gestionnaire') {
                            $link = route('gestionnaire.wallet-recharge.show', $notificationData['wallet_recharge_id']);
                        }
                    } elseif ($notificationType === 'artist_registration' && isset($notificationData['artist_id'])) {
                        if ($routePrefix === 'admin') {
                            $link = route('admin.view-artist', $notificationData['artist_id']);
                        }
                    }
                }

                // Default fallback: use notification view route
                if (!$link) {
                    $link = route($routePrefix . '.notifications.view', $notification);
                }

                // Determine icon type and color
                $iconClass = 'general';
                $iconSymbol = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM12 11.5C10.62 11.5 9.5 10.38 9.5 9C9.5 7.62 10.62 6.5 12 6.5C13.38 6.5 14.5 7.62 14.5 9C14.5 10.38 13.38 11.5 12 11.5Z" fill="#193948"/></svg>';
                
                if (str_contains($notificationType, 'complaint')) {
                    $iconClass = 'complaint';
                    $iconSymbol = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 2H4C2.9 2 2 2.9 2 4V22L6 18H20C21.1 18 22 17.1 22 16V4C22 2.9 21.1 2 20 2ZM20 16H6L4 18V4H20V16Z" fill="#193948"/></svg>';
                } elseif (str_contains($notificationType, 'artwork')) {
                    if (str_contains($notificationType, 'approved') || str_contains($notificationType, 'activated')) {
                        $iconClass = 'approved';
                        $iconSymbol = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM10 17L5 12L6.41 10.59L10 14.17L17.59 6.58L19 8L10 17Z" fill="#193948"/></svg>';
                    } else {
                        $iconClass = 'artwork';
                        $iconSymbol = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.89 22 12 22ZM18 16V11C18 7.93 16.36 5.36 13.5 4.68V4C13.5 3.17 12.83 2.5 12 2.5C11.17 2.5 10.5 3.17 10.5 4V4.68C7.63 5.36 6 7.92 6 11V16L4 18V19H20V18L18 16Z" fill="#193948"/></svg>';
                    }
                } elseif (str_contains($notificationType, 'wallet')) {
                    $iconClass = 'wallet';
                    $iconSymbol = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4C2.89 4 2.01 4.89 2.01 6L2 18C2 19.11 2.89 20 4 20H20C21.11 20 22 19.11 22 18V6C22 4.89 21.11 4 20 4ZM20 18H4V12H20V18ZM20 8H4V6H20V8Z" fill="#193948"/></svg>';
                }
            @endphp

            <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}">
                @if(!$notification->is_read)
                    <form action="{{ route($routePrefix . '.notifications.mark-read', $notification) }}" method="POST" style="margin: 0; display: inline; position: absolute; bottom: 0; right: 0; z-index: 10;" onsubmit="event.stopPropagation();">
                        @csrf
                        <button type="submit" class="notification-mark-read" title="Mark as Read">Mark as Read ✓</button>
                    </form>
                @endif
                
                <form action="{{ route($routePrefix . '.notifications.delete', $notification) }}" method="POST" style="margin: 0; display: inline; position: absolute; top: 0; right: 0; z-index: 10;" onsubmit="event.stopPropagation(); return confirm('Are you sure you want to delete this notification?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="notification-close" title="Delete Notification">×</button>
                </form>
                
                <div class="notification-card-icon {{ $iconClass }}">
                    {!! $iconSymbol !!}
                </div>
                
                <a href="{{ route($routePrefix . '.notifications.view', $notification) }}" style="text-decoration: none; color: inherit; display: flex; flex: 1; min-width: 0;">
                    <div class="notification-content" style="cursor: pointer;">
                        <h3 class="notification-title">{{ $notification->title }}</h3>
                        <p class="notification-message">{{ $notification->message }}</p>
                        <p class="notification-time">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div style="background-color: #F3EBDD; border: 2px solid #193948; border-radius: 0.75rem; padding: 3rem; text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">🔔</div>
                <h3 style="color: #193948; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                    No Notifications Yet
                </h3>
                <p style="color: #36454f; font-size: 0.9rem;">
                    You don't have any notifications at the moment.
                </p>
            </div>
        @endforelse

        @if($unreadCount > 0)
            <form action="{{ route($routePrefix . '.notifications.mark-all-read') }}" method="POST" style="margin: 0; text-align: center;">
                @csrf
                <button type="submit" class="mark-all-read-btn">
                    <span>✓</span>
                    <span>Mark All Read</span>
                </button>
            </form>
        @endif

        @if($notifications->hasPages())
            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

</x-allthepages-layout>
