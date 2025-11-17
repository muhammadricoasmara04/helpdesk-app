<?php

namespace App\Http\Controllers\Web\Staff;

use App\Events\TicketStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Illuminate\Http\Request;

class TicketStaffController extends Controller
{
    public function index()
    {
        return view('dashboard.staff.tickets.index');
    }

    public function assign($id, Request $request)
    {
        $user = $request->user();
        $ticket = Ticket::findOrFail($id);

        if ($ticket->assigned_to) {
            return response()->json(['message' => 'Tiket sudah di-handle oleh admin lain.'], 403);
        }

        $onProgressStatus = TicketStatus::where('name', 'In Progress')->first();

        $ticket->assigned_to = $user->id;
        if ($onProgressStatus) {
            $ticket->ticket_status_id = $onProgressStatus->id;
        }
        $ticket->save();
        broadcast(new TicketStatusUpdated($ticket))->toOthers();
        return response()->json([
            'message' => 'Tiket berhasil di-assign ke Anda dan status diperbarui menjadi On Progress.',
            'data' => $ticket
        ]);
    }
}
