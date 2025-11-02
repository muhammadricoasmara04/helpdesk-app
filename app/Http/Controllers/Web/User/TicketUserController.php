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
use App\Events\TicketCreated;
use App\Models\Organization;
use App\Models\TicketAttachment;

class TicketUserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $applications = Application::all();
        $problems = ApplicationProblem::all();
        $organization = $user->organization;
        return view('dashboard.users.ticket.store', compact('statuses', 'priorities', 'applications', 'problems', 'organization'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ticket_priority_id' => 'required|uuid',
            'application_id' => 'required|uuid',
            'application_problem_id' => 'required|uuid',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,docx',
        ]);

        $application = Application::findOrFail($validated['application_id']);
        $now = now();
        $month = $now->format('m');
        $year = $now->format('Y');

        $sequence = Ticket::where('application_id', $application->id)
            ->whereYear('created_at', $year)
            ->count() + 1;

        $numberFormatted = str_pad($sequence, 5, '0', STR_PAD_LEFT);

        $ticketCode = sprintf(
            '%s-TCK-%s/%s/%s',
            strtoupper($application->application_code),
            $numberFormatted,
            $month,
            $year
        );

        $ticket = new Ticket();
        $ticket->id = Str::uuid();
        $ticket->ticket_code = $ticketCode;
        $ticket->employee_number = Auth::id();
        $ticket->employee_name = Auth::user()->name;

        $openStatus = TicketStatus::where('slug', 'open')->first();
        if (!$openStatus) {
            return redirect()->back()->with('error', 'Status "open" tidak ditemukan di database.');
        }

        $ticket->ticket_status_id = $openStatus->id;
        $ticket->ticket_priority_id = $validated['ticket_priority_id'];
        $ticket->application_id = $validated['application_id'];
        $ticket->application_problem_id = $validated['application_problem_id'];
        $ticket->position_name = $request->input('position_name');
        $ticket->organization_name = $request->input('organization_name');
        $ticket->subject = $validated['subject'];
        $ticket->description = $validated['description'] ?? null;
        $ticket->save();

        // ✅ Simpan lampiran
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("users/attachments/{$ticket->id}", 'public');

                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                ]);
            }
        }


        event(new TicketCreated($ticket));

        return redirect()->back()->with('success', 'Tiket pengaduan berhasil dikirim.');
    }
}
