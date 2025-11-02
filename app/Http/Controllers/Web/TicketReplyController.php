<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketReplyController extends Controller
{
    public function index($ticket_id, Request $request)
    {
        $user = $request->user();
        $ticket = Ticket::findOrFail($ticket_id);
        return view('dashboard.admin.chat.index', compact('ticket'));
    }
}
