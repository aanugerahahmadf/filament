<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CctvStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cctvId;
    public $cctvName;
    public $status;
    public $location;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($cctvId, $cctvName, $status, $location)
    {
        $this->cctvId = $cctvId;
        $this->cctvName = $cctvName;
        $this->status = $status;
        $this->location = $location;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('cctv-status'),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'cctv.status.changed';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'cctv_id' => $this->cctvId,
            'cctv_name' => $this->cctvName,
            'status' => $this->status,
            'location' => $this->location,
            'timestamp' => $this->timestamp,
        ];
    }
}
