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
            <div class="flex flex-wrap items-center gap-3">

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


    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div
            class="p-5 border-b border-gray-200 flex justify-between items-center bg-linear-to-r from-blue-600 to-indigo-600">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                Daftar Tiket
            </h2>
            <a href="{{ route('user.ticket.create') }}"
                class="inline-flex items-center gap-2 bg-white text-blue-700 font-medium px-4 py-2 rounded-md shadow hover:bg-gray-100 transition">
                + Buat Tiket
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead>
                    <tr class="bg-blue-100 text-left text-gray-700 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3 font-semibold">Kode Tiket</th>
                        <th class="px-5 py-3 font-semibold">Subject</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Layanan</th>
                        <th class="px-5 py-3 font-semibold">Masalah</th>
                        <th class="px-5 py-3 font-semibold">Pelapor</th>
                        <th class="px-5 py-3 font-semibold">Tanggal Dibuat</th>
                        <th class="px-5 py-3 font-semibold">Tanggal Peruabahan</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tickets-table-body" class="divide-y divide-gray-100">


                    <!-- Placeholder saat loading -->
                    <tr>
                        <td colspan="9" class="text-center py-6 text-gray-400 italic">
                            Memuat data tiket...
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="pagination" class="flex justify-center items-center gap-2 mt-4"></div>
        </div>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard-user/dashboard.js')
@endpush
