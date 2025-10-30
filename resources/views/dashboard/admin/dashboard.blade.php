@extends('layouts.main-dashboard')

@section('container')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Tiket</h1>
        </div>

        <!-- Card Wrapper -->
        <div class="bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3">Kode Tiket</th>
                        <th class="px-5 py-3">Subject</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Prioritas</th>
                        <th class="px-5 py-3">Aplikasi</th>
                        <th class="px-5 py-3">Problem</th>
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
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard.js')
@endpush
