<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class TicketStatusUpdated implements ShouldBroadcastNow
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
        Log::info("🔥 EVENT DIKIRIM", [
            'ticket_id' => $this->ticket->id,
            'status' => $this->ticket->status->slug
        ]);
        return [
            'id' => $this->ticket->id,
            'status' => $this->ticket->status->slug,
            'status_name' => $this->ticket->status->name,
            'assigned_to' => $this->ticket->assignedTo ? $this->ticket->assignedTo->name : null,
        ];
    }

    public function broadcastAs(): string
    {
        return 'TicketStatusUpdated';
    }
}
