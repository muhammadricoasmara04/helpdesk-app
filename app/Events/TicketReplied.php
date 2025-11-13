<?php

namespace App\Events;

use App\Models\TicketReply;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TicketReplied implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $reply;
    public function __construct(TicketReply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new Channel('ticket.' . $this->reply->ticket_id)];
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->reply->id,
            'ticket_id' => $this->reply->ticket_id,
            'message' => $this->reply->message,
            'user_id' => $this->reply->user_id,
            'sender_name' => $this->reply->user->name ?? 'User',
            'created_at' => $this->reply->created_at->toIso8601String(),
            'is_read' => $this->reply->is_read ?? false,
        ];
    }

    public function broadcastAs()
    {
        // Nama event yang didengarkan di JS
        return 'TicketReplied';
    }
}
