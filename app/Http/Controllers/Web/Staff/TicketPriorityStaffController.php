<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use Illuminate\Http\Request;

class TicketPriorityStaffController extends Controller
{
    public function index()
    {
        return view('dashboard.staff.ticket-priority.index');
    }
    public function show($id)
    {
        $priority = TicketPriority::findOrFail($id);

        return view('dashboard.staff.ticket-priority.show', compact('priority'));
    }
}
