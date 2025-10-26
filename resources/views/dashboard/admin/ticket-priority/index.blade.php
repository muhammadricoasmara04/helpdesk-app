@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-4">Daftar Ticket Priority</h1>

    <a href="{{ route('ticket-priority-store') }}"
        class="block px-3 py-2 mb-4 bg-blue-600 text-white rounded-md w-fit hover:bg-blue-700 transition">
        Tambah Prioritas Baru
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Nama Prioritas</th>
                    <th class="border px-4 py-2 text-left">Slug</th>
                    <th class="border px-4 py-2 text-left">Deskripsi</th>
                    <th class="border px-4 py-2 text-left">Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody id="ticket-priority-table-body" class="bg-white">
                <tr>
                    <td colspan="4" class="text-center py-4 text-gray-500">Memuat data prioritas...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/ticket-priority.js')
@endpush
