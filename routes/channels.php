<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    // cek apakah user punya akses ke ticket
    $ticket = Ticket::find($ticketId);
    if (!$ticket) return false;
    return $user->id === $ticket->user_id || $user->is_admin;
});
