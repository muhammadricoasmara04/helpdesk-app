@extends('layouts.main-dashboard')

@section('container')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Masalah Layanan</h1>
        <a href="{{ route('application-problems-store') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors">
            + Tambah Permasalahan
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
        <table class="min-w-full border-collapse text-sm">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="text-gray-700 text-left">
                    <th class="px-6 py-3 border-b font-semibold">No</th>
                    <th class="px-6 py-3 border-b font-semibold">Masalah</th>
                    <th class="px-6 py-3 border-b font-semibold">Deskripsi</th>
                    <th class="px-6 py-3 border-b font-semibold">Layanan</th>
                    <th class="px-6 py-3 border-b font-semibold">Dibuat Oleh</th>
                    <th class="px-6 py-3 border-b font-semibold">Diperbatui</th>
                    <th class="px-6 py-3 border-b font-semibold">Tanggal Dibuat</th>
                    <th class="px-6 py-3 border-b font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="problems-table-body" class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition">
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Memuat data masalah layanan...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/application-problem.js')
@endpush
