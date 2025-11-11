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
    public function uploadAttachment(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        // Validasi file
        $request->validate([
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:2048', // max 2MB
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                // Simpan ke tabel ticket_attachments (pastikan modelnya ada)
                $ticket->attachments()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return back()->with('success', 'Lampiran berhasil diunggah!');
    }
}
