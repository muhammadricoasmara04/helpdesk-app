<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationProblem;
use App\Models\Ticket;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketUserController extends Controller
{
    public function index()
    {
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $applications = Application::all();
        $problems = ApplicationProblem::all();
        return view('dashboard.users.ticket.store', compact('statuses', 'priorities', 'applications', 'problems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_status_id' => 'required|uuid',
            'ticket_priority_id' => 'required|uuid',
            'application_id' => 'required|uuid',
            'application_problem_id' => 'required|uuid',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ticket = new Ticket();
        $ticket->ticked_code = 'TCK-' . strtoupper(Str::random(8));

        // ambil dari user login
        $ticket->employee_number = Auth::id();
        $ticket->employee_name = Auth::user()->name;

        // isi dari dropdown
        $ticket->ticket_status_id = $validated['ticket_status_id'];
        $ticket->ticket_priority_id = $validated['ticket_priority_id'];
        $ticket->application_id = $validated['application_id'];
        $ticket->application_problem_id = $validated['application_problem_id'];

        // input lainnya
        $ticket->position_name = $request->input('position_name');
        $ticket->organization_name = $request->input('organization_name');
        $ticket->subject = $validated['subject'];
        $ticket->description = $validated['description'] ?? null;

        $ticket->save();

        return redirect()->back()->with('success', 'Tiket pengaduan berhasil dikirim.');
    }
}
