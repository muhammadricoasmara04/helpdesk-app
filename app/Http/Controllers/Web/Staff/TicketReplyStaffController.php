<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Http\Request;

class TicketReplyStaffController extends Controller
{
    public function index($ticket_id, Request $request)
    {
        $user = $request->user();
        $statuses = TicketStatus::all();

        $ticket = Ticket::findOrFail($ticket_id);
        $priorities = TicketPriority::all();

        $staffRoleId = Role::where('name', 'staff')->first()->id;

        $staffList = User::where('role_id', $staffRoleId)->get();
        return view('dashboard.staff.chat.index', compact('ticket', 'priorities', 'statuses', 'staffList'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'ticket_priority_id' => 'required|exists:ticket_priority,id',
            'ticket_status_id' => 'required|exists:ticket_status,id',
        ]);

        $ticket = Ticket::findOrFail($id);
        $ticket->update([
            'ticket_priority_id' => $request->ticket_priority_id,
            'ticket_status_id' => $request->ticket_status_id,
        ]);

        return back()->with('success', 'Status dan Prioritas tiket berhasil diperbarui.');
    }


    public function delegate(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id'
        ]);

        // Update assigned staff
        $ticket->assigned_to = $request->assigned_to;
        $ticket->save();

        return redirect()->route('staff.ticket.index')
            ->with('success', 'Tiket berhasil didelegasikan.');
    }
}
