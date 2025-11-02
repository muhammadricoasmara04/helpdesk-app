@extends('layouts.main-dashboard')

@section('container')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Tiket</h1>
        </div>

        <div class="flex flex-wrap -mx-3 mt-6">
            <!-- Total Tickets -->
            <div class="w-full sm:w-1/2 xl:w-1/4 px-3 mb-4">
                <div class="flex items-center px-4 py-4 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $totalTickets }}</h4>
                        <div class="text-gray-500">Total Tickets</div>
                    </div>
                </div>
            </div>

            <!-- Open Tickets -->
            <div class="w-full sm:w-1/2 xl:w-1/4 px-3 mb-4">
                <div class="flex items-center px-4 py-4 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-orange-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none">
                            <path d="M4.2 1.4H6.99999L8.25 13.57L6.99992 14.82H21" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $openTickets }}</h4>
                        <div class="text-gray-500">Open Tickets</div>
                    </div>
                </div>
            </div>

            <!-- On Progress Tickets -->
            <div class="w-full sm:w-1/2 xl:w-1/4 px-3 mb-4">
                <div class="flex items-center px-4 py-4 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-pink-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none">
                            <path d="M6.99998 11.2H21L22.4 23.8H5.59998L6.99998 11.2Z" fill="currentColor" />
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $onProgressTickets }}</h4>
                        <div class="text-gray-500">Progress Tickets</div>
                    </div>
                </div>
            </div>

            <!-- Closed Tickets -->
            <div class="w-full sm:w-1/2 xl:w-1/4 px-3 mb-4">
                <div class="flex items-center px-4 py-4 shadow-sm rounded-md bg-white">
                    <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 28 28" fill="none">
                            <path d="M9 12l2 2 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="mx-4">
                        <h4 class="text-2xl font-semibold text-gray-700">{{ $closedTickets }}</h4>
                        <div class="text-gray-500">Closed Tickets</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Card Table -->
        <a href="{{ route('ticket.index') }}"
            class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            ➕ Buat Tiket Pengaduan
        </a>
        <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3">Kode Tiket</th>
                        <th class="px-5 py-3">Subjek</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Prioritas</th>
                        <th class="px-5 py-3">Aplikasi</th>
                        <th class="px-5 py-3">Masalah</th>
                        <th class="px-5 py-3">Nama Pegawai</th>
                        <th class="px-5 py-3">Dibuat Pada</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tickets-table-body" class="divide-y divide-gray-100">
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400 italic">
                            Memuat data tiket...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard-user/dashboard.js')
@endpush
