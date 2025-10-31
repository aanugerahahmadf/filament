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

class NotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->notification->user_id);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'notification.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'type' => $this->notification->type,
            'message' => $this->getMessageFromNotification(),
            'data' => $this->notification->data,
            'created_at' => $this->notification->created_at->toISOString(),
        ];
    }

    /**
     * Extract message from notification data.
     *
     * @return string
     */
    private function getMessageFromNotification()
    {
        // Check if message is directly in the notification
        if ($this->notification->message) {
            return $this->notification->message;
        }

        // Check if message is in the data object
        if ($this->notification->data && is_array($this->notification->data)) {
            if (isset($this->notification->data['message'])) {
                return $this->notification->data['message'];
            }

            // If data contains other fields, create a message from them
            $dataKeys = array_keys($this->notification->data);
            if (count($dataKeys) > 0) {
                return $dataKeys[0] . ': ' . $this->notification->data[$dataKeys[0]];
            }
        }

        return 'New notification';
    }
}
