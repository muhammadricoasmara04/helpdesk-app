<?php

namespace App\Http\Controllers\Web\User;

use App\Events\AttachmentUploaded;
use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            'attachments'   => 'nullable|array',
            'attachments.*' => 'file|max:5120|mimes:jpg,jpeg,png,pdf,docx',
        ]);

        // Hitung yang sudah tersimpan
        $existingCount = $ticket->attachments()->count();

        // Hitung yang akan di-upload sekarang
        $uploadCount = $request->hasFile('attachments')
            ? count($request->file('attachments'))
            : 0;

        // Cek apakah melebihi 5 file total
        if ($existingCount + $uploadCount > 5) {
            return back()->with('error', 'Maksimal 5 lampiran per tiket.');
        }

        // Simpan file jika tidak melebihi kuota
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store("users/attachments/{$ticket->id}", 'public');

                $ticket->attachments()->create([
                    'id'        => Str::uuid(),
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                ]);
                event(new AttachmentUploaded(
                    $ticket->id,
                    asset('storage/' . $path),
                    $file->getClientOriginalExtension(),
                    Auth::user()->name,
                    Auth::id()
                ));
            }
        }

        return back()->with('success', 'Lampiran berhasil diunggah!');
    }
}
