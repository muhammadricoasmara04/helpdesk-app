@extends('layouts.main-dashboard')

@section('container')
<div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8 flex justify-center">
    <div class="max-w-2xl w-full">
        {{-- Header --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-blue-300 pb-3">
            🏷️ Detail Ticket Priority
        </h1>

        {{-- Card --}}
        <div class="bg-white shadow-xl rounded-3xl p-8 border border-gray-200 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Nama --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Prioritas</span>
                <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $priority->name }}</span>
            </div>

            {{-- Deskripsi --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Deskripsi</span>
                <span class="text-gray-900 mt-2 sm:mt-0">{{ $priority->description ?? '-' }}</span>
            </div>

            {{-- Slug --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Slug</span>
                <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $priority->slug }}</span>
            </div>

            {{-- Buttons --}}
            {{-- <div class="flex flex-col sm:flex-row justify-end mt-6 gap-3">
                <a href="{{ route('ticket-priority.index') }}"
                    class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium shadow-md transition">
                    ↩️ Kembali
                </a>
                <a href="{{ route('ticket-priority.edit', $priority->id) }}"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
                    ✏️ Edit
                </a>
            </div> --}}

        </div>
    </div>
</div>
@endsection
