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

        // DATA REALTIME
        $totalTickets = Ticket::where('employee_number', $userId)->count();

        $openTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'open'))
            ->count();

        $onProgressTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'in-progress'))
            ->count();

        $pendingTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'pending'))
            ->count();

        $closedTickets = Ticket::where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'closed'))
            ->count();

        // Tahun grafik
        $year = $request->input('year', now()->year);

        // ================================
        // QUERY PER BULAN (SEMUA STATUS)
        // ================================

        // OPEN (created_at)
        $openQuery = Ticket::select(
            DB::raw("EXTRACT(MONTH FROM created_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'open'))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw("EXTRACT(MONTH FROM created_at)"))
            ->get();

        // IN PROGRESS (updated_at)
        $progressQuery = Ticket::select(
            DB::raw("EXTRACT(MONTH FROM updated_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'in-progress'))
            ->whereYear('updated_at', $year)
            ->groupBy(DB::raw("EXTRACT(MONTH FROM updated_at)"))
            ->get();

        // PENDING
        $pendingQuery = Ticket::select(
            DB::raw("EXTRACT(MONTH FROM updated_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'pending'))
            ->whereYear('updated_at', $year)
            ->groupBy(DB::raw("EXTRACT(MONTH FROM updated_at)"))
            ->get();

        // CLOSED
        $closedQuery = Ticket::select(
            DB::raw("EXTRACT(MONTH FROM updated_at) as month"),
            DB::raw("COUNT(*) as total")
        )
            ->where('employee_number', $userId)
            ->whereHas('status', fn($q) => $q->where('slug', 'closed'))
            ->whereYear('updated_at', $year)
            ->groupBy(DB::raw("EXTRACT(MONTH FROM updated_at)"))
            ->get();

        // ================================
        // SIAPKAN ARRAY 12 BULAN (0 default)
        // ================================
        $openPM      = array_fill(1, 12, 0);
        $progressPM  = array_fill(1, 12, 0);
        $pendingPM   = array_fill(1, 12, 0);
        $closedPM    = array_fill(1, 12, 0);

        foreach ($openQuery as $d) {
            $openPM[(int)$d->month] = $d->total;
        }

        foreach ($progressQuery as $d) {
            $progressPM[(int)$d->month] = $d->total;
        }

        foreach ($pendingQuery as $d) {
            $pendingPM[(int)$d->month] = $d->total;
        }

        foreach ($closedQuery as $d) {
            $closedPM[(int)$d->month] = $d->total;
        }

        // Nama bulan
        $months = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        return view('dashboard.users.dashboard', [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'onProgressTickets' => $onProgressTickets,
            'pendingTickets' => $pendingTickets,
            'closedTickets' => $closedTickets,

            'year' => $year,
            'months' => $months,

            'openTicketsPerMonth' => array_values($openPM),
            'onProgressTicketsPerMonth' => array_values($progressPM),
            'pendingTicketsPerMonth' => array_values($pendingPM),
            'closedTicketsPerMonth' => array_values($closedPM),
        ]);
    }
}
