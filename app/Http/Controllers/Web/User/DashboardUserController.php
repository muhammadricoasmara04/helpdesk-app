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
    public function index(Request $request)
    {
        $userId = Auth::id();

        $totalTickets = Ticket::where('employee_number', $userId)->count();
        $openTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'open'))
            ->count();
        $onProgressTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'in-progress'))
            ->count();
        $closedTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'closed'))
            ->count();

        // === Analisis Ticket Closed per Bulan ===
        $year = $request->input('year', now()->year);

        $closedTicketsPerMonth = Ticket::select(
            DB::raw("EXTRACT(MONTH FROM updated_at) AS month"),
            DB::raw("COUNT(*) AS total")
        )
            ->where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'closed'))
            ->whereYear('updated_at', $year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM updated_at)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM updated_at)'))
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = $closedTicketsPerMonth[$m] ?? 0;
        }

        return view('dashboard.users.dashboard', compact(
            'totalTickets',
            'openTickets',
            'onProgressTickets',
            'closedTickets',
            'monthlyData',
            'year'
        ));
    }
}
