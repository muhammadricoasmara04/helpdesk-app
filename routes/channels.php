<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('ticket.{ticketId}', function ($user, $ticketId) {
    Log::info('CHANNEL AUTH CHECK', [
        'user_id' => $user->id,
        'ticket_id' => $ticketId,
    ]);

    // sementara izinkan semua pengguna agar bisa connect
    return true;
});
