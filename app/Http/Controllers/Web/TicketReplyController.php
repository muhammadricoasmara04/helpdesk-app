<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketPriority;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function index($ticket_id, Request $request)
    {
        $user = $request->user();
        $ticket = Ticket::findOrFail($ticket_id);
        $priorities = TicketPriority::all();
        return view('dashboard.admin.chat.index', compact('ticket', 'priorities'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'ticket_priority_id' => 'required|exists:ticket_priority,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'ticket_priority_id' => $request->ticket_priority_id,
        ]);

        return back()->with('success', 'Prioritas tiket berhasil diperbarui.');
    }
}
