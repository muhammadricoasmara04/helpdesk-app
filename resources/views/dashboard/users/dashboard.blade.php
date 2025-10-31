@extends('layouts.main-dashboard')

@section('container')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Tiket Saya</h1>

            <a href="{{ route('ticket.index') }}"
                class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                ➕ Buat Tiket Pengaduan
            </a>
        </div>

        <!-- Card Table -->
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
