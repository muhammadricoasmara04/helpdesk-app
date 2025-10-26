@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-4">Ticket Status</h1>

    <a href="{{ route('ticket-status-store') }}" class="block px-3 py-2 mb-4 rounded-md bg-blue-500 text-white w-fit">
        Tambah Status
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Nama</th>
                    <th class="border px-4 py-2 text-left">Deskripsi</th>
                    <th class="border px-4 py-2 text-left">Slug</th>
                </tr>
            </thead>
            <tbody id="ticketstatus-table-body" class="bg-white">
                <tr>
                    <td colspan="3" class="text-center py-4 text-gray-500">Memuat data...</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/ticket-status.js')
@endpush
