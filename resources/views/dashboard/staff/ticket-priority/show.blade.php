@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Detail Ticket Priority</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            <div>
                <h2 class="font-semibold text-gray-700">Nama:</h2>
                <p class="text-gray-900">{{ $priority->name }}</p>
            </div>

            <div>
                <h2 class="font-semibold text-gray-700">Deskripsi:</h2>
                <p class="text-gray-900">{{ $priority->description ?? '-' }}</p>
            </div>

            <div>
                <h2 class="font-semibold text-gray-700">Slug:</h2>
                <p class="text-gray-900">{{ $priority->slug }}</p>
            </div>
        </div>

        {{-- <div class="mt-6 flex justify-end space-x-3">
            <a href="{{ route('ticket-status') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                Kembali
            </a>

            <a href="{{ route('ticket-priority.edit', $priority->id) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                Edit
            </a>
        </div> --}}
    </div>
@endsection
