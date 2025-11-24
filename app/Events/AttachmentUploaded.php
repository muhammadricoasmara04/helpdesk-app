<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentUploaded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketId;
    public $fileUrl;
    public $fileType;
    public $senderName;
    public $userId;
    /**
     * Create a new event instance.
     */
    public function __construct($ticketId, $fileUrl, $fileType, $senderName, $userId)
    {
        $this->ticketId = $ticketId;
        $this->fileUrl = $fileUrl;
        $this->fileType = $fileType;
        $this->senderName = $senderName;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return [new Channel('ticket.' . $this->ticketId)];
    }

    public function broadcastAs()
    {
        return 'AttachmentUploaded';
    }
}
