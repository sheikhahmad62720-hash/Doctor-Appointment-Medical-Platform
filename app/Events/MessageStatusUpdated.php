<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class MessageStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public Message $message,
        public bool $delivered,
        public bool $read,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.'.$this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.status';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->message->conversation_id,
            'message_id' => $this->message->id,
            'is_delivered' => $this->delivered,
            'is_read' => $this->read,
        ];
    }
}
