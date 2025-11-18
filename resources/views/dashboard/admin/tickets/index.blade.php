@extends('layouts.main-dashboard')

@section('container')
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <!-- Search bar -->
        <input id="search-input" type="text" placeholder="Cari tiket berdasarkan subject, kode, atau pelapor..."
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 w-full md:w-auto focus:ring focus:ring-blue-200 focus:border-blue-400 transition" />

        <!-- Filters + Reset -->
        <div class="flex flex-wrap gap-2 md:gap-3 items-center">
            <select id="status-filter"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-400 transition">
                <option value="">Semua Status</option>
                <option value="open">Open</option>
                <option value="in-progress">On Progress</option>
                <option value="closed">Closed</option>
            </select>

            <select id="priority-filter"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 focus:border-blue-400 transition">
                <option value="">Semua Prioritas</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>

            <button id="reset-filter" class="bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded transition">
                Reset
            </button>
        </div>
    </div>


    <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">

        <div class="overflow-x-auto"> <!-- **** wrapper scroll horizontal **** -->
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3">Kode Tiket</th>
                        <th class="px-5 py-3">Subject</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Prioritas</th>
                        <th class="px-5 py-3">Layanan</th>
                        <th class="px-5 py-3">Masalah</th>
                        <th class="px-5 py-3">Pelapor</th>
                        <th class="px-5 py-3">Tanggal Dibuat</th>
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
