@extends('layouts.main-dashboard')

@section('container')
    <div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8 flex justify-center">
        <div class="max-w-2xl w-full bg-white shadow-xl rounded-3xl p-8 border border-gray-200">
            {{-- Header --}}
            <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-3">📌 Detail Ticket Status</h1>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Details --}}
            <div class="space-y-6">
                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-gray-700">Nama</h2>
                    <p class="text-gray-900 mt-2 sm:mt-0">{{ $status->name }}</p>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-gray-700">Deskripsi</h2>
                    <p class="text-gray-900 mt-2 sm:mt-0">{{ $status->description ?? '-' }}</p>
                </div>

                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                    <h2 class="font-semibold text-gray-700">Slug</h2>
                    <p class="text-gray-900 mt-2 sm:mt-0">{{ $status->slug }}</p>
                </div>
            </div>

            {{-- Action Buttons --}}
            {{-- <div class="mt-8 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('ticket-status') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-3 rounded-xl font-medium shadow-md text-center transition">
                    ↩️ Kembali
                </a>

                <a href="{{ route('ticket-status.edit', $status->id) }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium shadow-md text-center transition">
                    ✏️ Edit
                </a>
            </div> --}}
        </div>
    </div>
@endsection
