@extends('layouts.main-dashboard')
@section('container')
    <h1 class="text-2xl font-bold mb-4">Dashboard Aplikasi</h1>

    <a href="{{ route('application-store') }}"
        class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
        Tambah Aplikasi
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Nama Aplikasi</th>
                    <th class="border px-4 py-2 text-left">Deskripsi</th>
                    <th class="border px-4 py-2 text-left">Dibuat Oleh</th>
                    <th class="border px-4 py-2 text-left">Diperbarui Oleh</th>
                    <th class="border px-4 py-2 text-left">Tanggal Dibuat</th>
                    <th class="border px-4 py-2 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody id="application-table-body" class="bg-white">
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Memuat data aplikasi...</td>
                </tr>
            </tbody>
        </table>
        <div id="user-info"></div>

    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/application.js')
@endpush
