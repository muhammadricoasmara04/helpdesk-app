<?php

namespace App\Http\Controllers\Api;

use App\Events\TicketRead;
use App\Events\TicketStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\TicketReply;
use App\Events\TicketReplied;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TicketReplyController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_id' => 'required|uuid|exists:tickets,id',
            'message' => 'required|string',
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $validated['ticket_id'],
            'message' => $validated['message'],
            'user_id' => Auth::id(),
            'is_read' => false,
        ]);

        broadcast(new TicketReplied($reply))->toOthers();


        return response()->json([
            'success' => true,
            'data' => $reply,
        ]);
    }
    public function show($ticket_id)
    {
        $userId = Auth::id();

        // Ambil semua pesan
        $replies = TicketReply::where('ticket_id', $ticket_id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($reply) use ($userId) {
                $reply->is_own = $reply->user_id === $userId;
                return $reply;
            });

        $updated = TicketReply::where('ticket_id', $ticket_id)
            ->where('user_id', '!=', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // 🟢 Broadcast event jika ada yang berubah
        if ($updated > 0) {
            broadcast(new TicketRead($ticket_id, $userId))->toOthers();
        }


        return response()->json([
            'success' => true,
            'data' => $replies,
        ]);
    }
    public function close($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->ticket_status_id = TicketStatus::where('slug', 'closed')->first()->id;
        $ticket->save();
        $ticket->load('status');
        Log::info("🔥 USER MENUTUP TICKET, SIAP BROADCAST", ['id' => $ticket->id]);

        broadcast(new TicketStatusUpdated($ticket))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'Ticket closed successfully',
            'data' => $ticket,
        ]);
    }
}
