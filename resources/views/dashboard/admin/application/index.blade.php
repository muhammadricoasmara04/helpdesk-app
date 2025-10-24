@extends('layouts.main-dashboard')
@section('container')
    <h1 class="text-2xl font-bold mb-4">Dashboard Aplikasi</h1>

    <a href="{{ route('application-store') }}"
        class="block px-3 py-2 rounded-md">
        Tambah Aplikasi
    </a>
    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Nama Aplikasi</th>
                    <th class="border px-4 py-2 text-left">Deskripsi</th>
                    <th class="border px-4 py-2 text-left">Pembuatan ID</th>
                    <th class="border px-4 py-2 text-left">Pembaruan ID</th>
                    <th class="border px-4 py-2 text-left">Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody id="application-table-body" class="bg-white">
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-500">Memuat data tiket...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard/application.js')
@endpush
