<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();

        $openTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'open'))->count();

        $onProgressTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'in-progress'))->count();

        $closedTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'closed'))->count();

        return view('dashboard.admin.dashboard', compact(
            'totalTickets',
            'openTickets',
            'onProgressTickets',
            'closedTickets'
        ));
    }
}
