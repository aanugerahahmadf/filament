<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MapDataChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $entity;

    public string $action;

    public array $payload;

    public function __construct(string $entity, string $action, array $payload = [])
    {
        $this->entity = $entity;
        $this->action = $action;
        $this->payload = $payload;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('maps-updates');
    }

    public function broadcastAs(): string
    {
        return 'maps.data.changed';
    }
}
