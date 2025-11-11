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
        $totalTickets = Ticket::count();
        $openTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'open'))->count();
        $onProgressTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'in-progress'))->count();
        $closedTickets = Ticket::whereHas('status', fn($q) => $q->where('slug', 'closed'))->count();

        $year = $request->input('year', now()->year);
        $closedTicketsMonthly = DB::table('tickets')
            ->join('ticket_status', 'tickets.ticket_status_id', '=', 'ticket_status.id')
            ->select(
                DB::raw('EXTRACT(MONTH FROM tickets.updated_at) AS month'),
                DB::raw('COUNT(*) AS total')
            )
            ->where('ticket_status.slug', 'closed')
            ->whereRaw('EXTRACT(YEAR FROM tickets.updated_at) = ?', [$year])
            ->groupBy(DB::raw('EXTRACT(MONTH FROM tickets.updated_at)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM tickets.updated_at)'))
            ->get();

        // Siapkan daftar bulan
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

        // Inisialisasi semua bulan dengan nilai 0
        $closedTicketsPerMonth = array_fill(1, 12, 0);

        // Isi data berdasarkan hasil query
        foreach ($closedTicketsMonthly as $data) {
            $month = (int) $data->month;
            $closedTicketsPerMonth[$month] = $data->total;
        }

        return view('dashboard.admin.dashboard', [
            'totalTickets' => $totalTickets,
            'openTickets' => $openTickets,
            'onProgressTickets' => $onProgressTickets,
            'closedTickets' => $closedTickets,
            'year' => $year,
            'months' => array_values($months),
            'closedTicketsPerMonth' => array_values($closedTicketsPerMonth),
        ]);
    }
}
