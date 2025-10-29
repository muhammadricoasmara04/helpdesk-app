<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketUserReplyController extends Controller
{
    public function index($ticket_id)
    {
        $ticket = Ticket::findOrFail($ticket_id);

        return view('dashboard.users.chat.index', compact('ticket'));
    }
}
