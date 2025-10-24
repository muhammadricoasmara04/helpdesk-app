@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-4">Dashboard Tiket</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Kode Tiket</th>
                    <th class="border px-4 py-2 text-left">Subject</th>
                    <th class="border px-4 py-2 text-left">Status</th>
                    <th class="border px-4 py-2 text-left">Prioritas</th>
                    <th class="border px-4 py-2 text-left">Aplikasi</th>
                    <th class="border px-4 py-2 text-left">Problem</th>
                    <th class="border px-4 py-2 text-left">Pelapor</th>
                    <th class="border px-4 py-2 text-left">Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody id="tickets-table-body" class="bg-white">
                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500">Memuat data tiket...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush