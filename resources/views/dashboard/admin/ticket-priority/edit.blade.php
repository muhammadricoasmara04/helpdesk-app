@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Ticket Status</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif
        <p>Logged in user: {{ Auth::user() ? Auth::user()->name : 'Not logged in' }}</p>
        <p>Role: {{ Auth::user()?->role ?? 'N/A' }}</p>
        <form action="{{ url('/dashboard/ticket-priority/' . $priority->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
                <p>URL update: {{ route('ticket-status.update', $priority->id) }}</p>
                <label for="name" class="block font-semibold text-gray-700">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $priority->name) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block font-semibold text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $priority->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="/dashboard/ticket-priority"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                    Batal
                </a>

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
