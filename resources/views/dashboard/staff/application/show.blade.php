@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Detail Aplikasi</h1>

    <div id="applicationDetail" class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <div class="space-y-3">
            <p><strong>Nama Aplikasi:</strong> <span id="application_name"></span></p>
            <p><strong>Kode Aplikasi:</strong> <span id="application_code"></span></p>
            <p><strong>Deskripsi:</strong> <span id="description"></span></p>
            <p><strong>Organization ID:</strong> <span id="organization_id"></span></p>
            <p><strong>Dibuat oleh:</strong> <span id="create_id"></span></p>
            <p><strong>Diperbarui oleh:</strong> <span id="updated_id"></span></p>
        </div>

        {{-- <div class="flex justify-end mt-6 space-x-2">
            <a href="{{ url('/dashboard/application/' . request()->segment(3) . '/edit') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Edit</a>

            <a href="{{ url('/dashboard/application') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Kembali</a>
        </div> --}}
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/application.js')
@endpush
