<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Notification $notification, int $userId)
    {
        $this->notification = $notification;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $data = $this->notification->data ?? [];
        $relatedId = null;
        
        // Extract related_id from data if exists
        if (isset($data['artwork_id'])) {
            $relatedId = $data['artwork_id'];
        } elseif (isset($data['complaint_id'])) {
            $relatedId = $data['complaint_id'];
        } elseif (isset($data['mission_id'])) {
            $relatedId = $data['mission_id'];
        } elseif (isset($data['pv_id'])) {
            $relatedId = $data['pv_id'];
        } elseif (isset($data['wallet_recharge_id'])) {
            $relatedId = $data['wallet_recharge_id'];
        }

        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'type' => $this->notification->type,
            'related_id' => $relatedId,
            'is_read' => $this->notification->is_read,
            'created_at' => $this->notification->created_at->toISOString(),
        ];
    }
}

