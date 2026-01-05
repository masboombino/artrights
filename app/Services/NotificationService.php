<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * @param  User|Collection|array<int, User|int>|int|null  $recipients
     */
    public static function send($recipients, string $title, string $message, array $data = []): void
    {
        $users = self::resolveRecipients($recipients);

        if ($users->isEmpty()) {
            return;
        }

        $currentUser = auth()->user();
        $type = $data['type'] ?? 'general';
        $senderId = $currentUser ? $currentUser->id : null;
        $senderType = $currentUser ? self::getUserRole($currentUser) : null;

        $now = now();
        $payload = $users->map(function (User $user) use ($title, $message, $data, $type, $senderId, $senderType, $now) {
            return [
                'user_id' => $user->id,
                'type' => $type,
                'sender_id' => $senderId,
                'sender_type' => $senderType,
                'title' => $title,
                'message' => $message,
                'data' => !empty($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : null,
                'is_read' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->all();

        Notification::insert($payload);
    }

    public static function sendToAgencyRole(string $role, ?int $agencyId, string $title, string $message, array $data = []): void
    {
        if (!$agencyId) {
            return;
        }

        $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->where('agency_id', $agencyId)
            ->get();

        self::send($users, $title, $message, $data);
    }

    /**
     * @param  User|Collection|array<int, User|int>|int|null  $recipients
     */
    protected static function resolveRecipients($recipients): Collection
    {
        if ($recipients instanceof User) {
            return collect([$recipients]);
        }

        if ($recipients instanceof Collection) {
            return $recipients->filter()->unique('id')->values();
        }

        if (is_array($recipients)) {
            $models = [];
            $ids = [];

            foreach ($recipients as $recipient) {
                if ($recipient instanceof User) {
                    $models[] = $recipient;
                } elseif (is_numeric($recipient)) {
                    $ids[] = (int) $recipient;
                }
            }

            if (!empty($ids)) {
                $models = array_merge(
                    $models,
                    User::whereIn('id', array_unique($ids))->get()->all()
                );
            }

            return collect($models)->filter()->unique('id')->values();
        }

        if (is_numeric($recipients)) {
            $user = User::find((int) $recipients);

            return $user ? collect([$user]) : collect();
        }

        return collect();
    }

    protected static function getUserRole(User $user): ?string
    {
        if ($user->hasRole('super_admin')) {
            return 'super_admin';
        } elseif ($user->hasRole('admin')) {
            return 'admin';
        } elseif ($user->hasRole('gestionnaire')) {
            return 'gestionnaire';
        } elseif ($user->hasRole('agent')) {
            return 'agent';
        } elseif ($user->hasRole('artist')) {
            return 'artist';
        }

        return null;
    }
}


