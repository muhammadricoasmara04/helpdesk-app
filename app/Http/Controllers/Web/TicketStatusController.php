<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketStatusController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.ticket-status.index');
    }
    public function store()
    {
        return view('dashboard.admin.ticket-status.store');
    }

    public function show($id)
    {
        $status = TicketStatus::findOrFail($id);

        return view('dashboard.admin.ticket-status.show', compact('status'));
    }

    public function edit($id)
    {
        $status = TicketStatus::findOrFail($id);
        return view('dashboard.admin.ticket-status.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $status = TicketStatus::findOrFail($id);
        $status->update($validated);
        return redirect()->route('ticket-status')->with('success', 'Ticket Status berhasil diperbarui.');
    }
}
