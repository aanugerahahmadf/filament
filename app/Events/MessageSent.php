<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message) {}

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('user.'.$this->message->to_user_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'from_user' => [
                'id' => $this->message->fromUser->id,
                'name' => $this->message->fromUser->name,
            ],
            'to_user' => [
                'id' => $this->message->toUser->id,
                'name' => $this->message->toUser->name,
            ],
            'body' => $this->message->body,
            'created_at' => $this->message->created_at?->toDateTimeString(),
        ];
    }
}
