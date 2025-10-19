<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $alert;

    public function __construct(array $alert)
    {
        $this->alert = $alert;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('emergency-alerts');
    }

    public function broadcastAs(): string
    {
        return 'emergency.alert';
    }
}
