<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use Illuminate\Http\Request;

class TicketPriorityController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.ticket-priority.index');
    }

    public function store()
    {
        return view('dashboard.admin.ticket-priority.store');
    }
    public function show($id)
    {
        $priority = TicketPriority::findOrFail($id);

        return view('dashboard.admin.ticket-priority.show', compact('priority'));
    }
    public function edit($id)
    {
        $priority =  TicketPriority::findOrFail($id);
        return view('dashboard.admin.ticket-priority.edit', compact('priority'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $priority =  TicketPriority::findOrFail($id);
        $priority->update($validated);
        return redirect()->route('ticket-priority')->with('success', 'Ticket Status berhasil diperbarui.');
    }
}
