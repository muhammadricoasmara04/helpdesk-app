<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // COUNT TOTAL TICKET PER STATUS (REALTIME)
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'open'))->count();
        $onProgressTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'in-progress'))->count();
        $pendingTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'pending'))->count();
        $closedTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'closed'))->count();

        // Tahun dipilih user atau default tahun ini
        $year = $request->input('year', now()->year);

        // ============================
        // 1. Closed Tickets Per Month
        // ============================
        $closedQuery = Ticket::join('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tickets.updated_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->where('ticket_status.slug', 'closed')
            ->whereRaw("EXTRACT(YEAR FROM tickets.updated_at) = $year")
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tickets.updated_at)'))
            ->get();

        // ============================
        // 2. Open Tickets Per Month
        // ============================
        $openQuery = Ticket::join('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tickets.created_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->where('ticket_status.slug', 'open')
            ->whereRaw("EXTRACT(YEAR FROM tickets.created_at) = $year")
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tickets.created_at)'))
            ->get();

        // ============================
        // 3. In-Progress Tickets Per Month
        // ============================
        $progressQuery = Ticket::join('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tickets.updated_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->where('ticket_status.slug', 'in-progress')
            ->whereRaw("EXTRACT(YEAR FROM tickets.updated_at) = $year")
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tickets.updated_at)'))
            ->get();

        // ============================
        // 4. Pending Tickets Per Month
        // ============================
        $pendingQuery = Ticket::join('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tickets.updated_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->where('ticket_status.slug', 'pending')
            ->whereRaw("EXTRACT(YEAR FROM tickets.updated_at) = $year")
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tickets.updated_at)'))
            ->get();

        // ============================
        // Daftar bulan
        // ============================
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // ============================
        // Inisialisasi 12 bulan ke 0
        // ============================
        $openPM = $progressPM = $pendingPM = $closedPM = array_fill(1, 12, 0);

        // Masukkan hasil query ke array
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

        // ============================
        // Return ke Blade
        // ============================
        return view('dashboard.admin.dashboard', [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'onProgressTickets' => $onProgressTickets,
            'pendingTickets' => $pendingTickets,
            'closedTickets' => $closedTickets,

            'year' => $year,
            'months' => array_values($months),

            'openTicketsPerMonth' => array_values($openPM),
            'onProgressTicketsPerMonth' => array_values($progressPM),
            'pendingTicketsPerMonth' => array_values($pendingPM),
            'closedTicketsPerMonth' => array_values($closedPM),
        ]);
    }
}
