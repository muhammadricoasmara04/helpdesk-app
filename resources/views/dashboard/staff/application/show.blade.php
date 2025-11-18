@extends('layouts.main-dashboard')

@section('container')
<div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8 flex justify-center">
    <div class="max-w-3xl w-full">
        {{-- Header --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-8 border-b pb-3">Detail Layanan</h1>

        {{-- Card --}}
        <div id="applicationDetail" class="bg-white shadow-xl rounded-3xl p-8 border border-gray-200">
            <div class="space-y-6">

                <!-- Nama Layanan -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Nama Layanan</span>
                    <span class="text-gray-900 font-semibold mt-2 sm:mt-0" id="application_name"></span>
                </div>

                <!-- Kode Layanan -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Kode Layanan</span>
                    <span class="text-gray-900 font-semibold mt-2 sm:mt-0" id="application_code"></span>
                </div>

                <!-- Deskripsi -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Deskripsi</span>
                    <span class="text-gray-900 mt-2 sm:mt-0" id="description"></span>
                </div>

                <!-- Unit Kerja -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Unit Kerja</span>
                    <span class="text-gray-900 font-semibold mt-2 sm:mt-0" id="organization_id"></span>
                </div>

                <!-- Dibuat oleh -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Dibuat oleh</span>
                    <span class="text-gray-900 mt-2 sm:mt-0" id="create_id"></span>
                </div>

                <!-- Diperbarui oleh -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <span class="text-gray-600 font-medium">Diperbarui oleh</span>
                    <span class="text-gray-900 mt-2 sm:mt-0" id="updated_id"></span>
                </div>

            </div>

            <!-- Buttons -->
            {{-- <div class="flex flex-col sm:flex-row justify-end mt-10 gap-3">
                <a href="{{ url('/dashboard/application/' . request()->segment(3) . '/edit') }}"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md text-center transition">
                    ✏️ Edit
                </a>

                <a href="{{ url('/dashboard/application') }}"
                    class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium shadow-md text-center transition">
                    ↩️ Kembali
                </a>
            </div> --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/application.js')
@endpush
