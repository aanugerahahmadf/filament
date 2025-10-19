<?php

namespace App\Events;

use App\Models\Cctv;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CctvStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Cctv $cctv) {}

    public function broadcastOn(): array
    {
        return [new Channel('cctv-status')];
    }

    public function broadcastAs(): string
    {
        return 'CctvStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->cctv->id,
            'status' => $this->cctv->status,
            'last_seen_at' => optional($this->cctv->last_seen_at)->toISOString(),
            'hls_path' => $this->cctv->hls_path,
        ];
    }
}
