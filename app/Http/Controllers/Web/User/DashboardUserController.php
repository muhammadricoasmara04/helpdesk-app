<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardUserController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        // Ambil jumlah tiket per user
        $totalTickets = Ticket::where('employee_number', $userId)->count();

        // Tiket berdasarkan status
        $openTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'open'))
            ->count();

        $onProgressTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'onprogress'))
            ->count();

        $closedTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'closed'))
            ->count();

        return view('dashboard.users.dashboard', compact(
            'totalTickets',
            'openTickets',
            'onProgressTickets',
            'closedTickets'
        ));
    }
}
