<?php

namespace App\Http\Controllers\Web\Staff;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\Request;

class TicketStatusStaffController extends Controller
{
    public function index()
    {
        return view('dashboard.staff.ticket-status.index');
    }

    public function show($id)
    {
        $status = TicketStatus::findOrFail($id);

        return view('dashboard.staff.ticket-status.show', compact('status'));
    }
}
