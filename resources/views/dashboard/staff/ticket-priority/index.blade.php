@extends('layouts.main-dashboard')

@section('container')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Ticket Priority</h1>
        
    </div>
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
        <table class="min-w-full border-collapse text-sm">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="text-gray-700 text-left">
                    <th class="px-6 py-3 border-b font-semibold">No</th>
                    <th class="px-6 py-3 border-b font-semibold">Nama Prioritas</th>
                    <th class="px-6 py-3 border-b font-semibold">Slug</th>
                    <th class="px-6 py-3 border-b font-semibold">Deskripsi</th>
                    <th class="px-6 py-3 border-b font-semibold">Tanggal Dibuat</th>
                    <th class="px-6 py-3 border-b font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody id="ticket-priority-table-body" class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition">
                    <td colspan="6" class="text-center py-6 text-gray-500">
                        Memuat data prioritas...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/ticket-priority.js')
@endpush
