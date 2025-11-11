@extends('layouts.main-dashboard')

@section('container')
    <div class="bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
        <div
            class="p-5 border-b border-gray-200 flex justify-between items-center bg-linear-to-r from-blue-600 to-indigo-600">
            <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                Daftar Tiket
            </h2>
            <a href="{{ route('user.ticket.create') }}"
                class="inline-flex items-center gap-2 bg-white text-blue-700 font-medium px-4 py-2 rounded-md shadow hover:bg-gray-100 transition">
                + Buat Tiket
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-700">
                <thead>
                    <tr class="bg-blue-100 text-left text-gray-700 uppercase text-xs tracking-wider">
                        <th class="px-5 py-3 font-semibold">Kode Tiket</th>
                        <th class="px-5 py-3 font-semibold">Subject</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Prioritas</th>
                        <th class="px-5 py-3 font-semibold">Aplikasi</th>
                        <th class="px-5 py-3 font-semibold">Masalah</th>
                        <th class="px-5 py-3 font-semibold">Pelapor</th>
                        <th class="px-5 py-3 font-semibold">Tanggal Dibuat</th>
                        <th class="px-5 py-3 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody id="tickets-table-body" class="divide-y divide-gray-100">


                    <!-- Placeholder saat loading -->
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
