<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;

class TicketStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new Channel('ticket.' . $this->ticket->id)];
    }
    public function broadcastWith()
    {
        return [
            'id' => $this->ticket->id,
            'status' => $this->ticket->status->slug,
            'status_name' => $this->ticket->status->name,
        ];
    }

    public function broadcastAs(): string
    {
        return 'TicketStatusUpdated';
    }
}
