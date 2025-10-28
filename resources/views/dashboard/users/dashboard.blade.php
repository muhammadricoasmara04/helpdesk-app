@extends('layouts.main-dashboard')
@section('container')
    <h1 class="text-2xl font-bold mb-4">Dashboard Tiket Saya</h1>

    <a href="{{ route('ticket.index') }}"
        class="block px-3 py-2 mb-4 bg-blue-600 text-white rounded-md w-fit hover:bg-blue-700 transition">
        Buat Tiket Pengaduan
    </a>

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2">Kode Tiket</th>
                    <th class="border px-4 py-2">Subjek</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Prioritas</th>
                    <th class="border px-4 py-2">Aplikasi</th>
                    <th class="border px-4 py-2">Masalah</th>
                    <th class="border px-4 py-2">Nama Pegawai</th>
                    <th class="border px-4 py-2">Dibuat Pada</th>
                </tr>
            </thead>
            <tbody id="tickets-table-body">
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500">
                        Memuat data tiket...
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard-user/dashboard')
@endpush
