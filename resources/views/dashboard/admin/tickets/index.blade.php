@extends('layouts.main-dashboard')

@section('container')
    <div class="bg-white p-4 md:p-6 rounded-xl shadow-sm border border-gray-200 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <!-- Search bar -->
            <div class="relative flex-1">
                <input id="search-input" type="text" placeholder="Cari tiket berdasarkan subject, kode, atau pelapor..."
                    class="w-full border border-gray-300 rounded-xl pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition" />

                <!-- Icon -->
                <span class="absolute top-2.5 left-3 text-gray-400">
                    🔍
                </span>
            </div>

            <!-- Filter group -->
            <div class="flex flex-wrap gap-3">

                <select id="status-filter"
                    class="border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition bg-white">
                    <option value="">Semua Status</option>
                    <option value="open">Open</option>
                    <option value="in-progress">On Progress</option>
                    <option value="closed">Closed</option>
                </select>

                <select id="priority-filter"
                    class="hidden border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-300 focus:border-blue-400 transition bg-white">
                    <option value="">Semua Prioritas</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>

                <!-- Tanggal Tiket -->
                <div class="relative flex items-center px-2 py-1">

                    <!-- Label posisi absolute sehingga tidak mengangkat layout -->
                    <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-xs text-gray-600 font-medium">
                        Tanggal Tiket
                    </span>

                    <div class="flex items-center gap-2">
                        <input id="start-date" type="date"
                            class="border border-gray-300 rounded-xl px-4 py-2.5
            focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white" />

                        <span class="text-gray-500">—</span>

                        <input id="end-date" type="date"
                            class="border border-gray-300 rounded-xl px-4 py-2.5
            focus:ring-2 focus:ring-blue-300 focus:border-blue-400 bg-white" />
                    </div>

                </div>
                <button id="reset-filter"
                    class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl transition font-medium">
                    Reset
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3">Kode Tiket</th>
                        <th class="px-5 py-3">Subject</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Layanan</th>
                        <th class="px-5 py-3">Masalah</th>
                        <th class="px-5 py-3">Pelapor</th>
                        <th class="px-5 py-3">Tanggal Dibuat</th>
                        <th class="px-5 py-3">Tanggal Perubahan</th>
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
        <div id="pagination" class="flex justify-center items-center gap-2 mt-4 p-4"></div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush
