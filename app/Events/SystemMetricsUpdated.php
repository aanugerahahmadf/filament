<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemMetricsUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $metrics;

    public function __construct(array $metrics)
    {
        $this->metrics = $metrics;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('dashboard-monitoring');
    }

    public function broadcastAs(): string
    {
        return 'system.metrics.updated';
    }
}
