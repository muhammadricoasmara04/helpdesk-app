@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-xl p-6 border border-gray-200">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Ticket Status</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif
       
        <form action="{{ url('/dashboard/ticket-status/' . $status->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div>
               
                <label for="name" class="block font-semibold text-gray-700">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $status->name) }}"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block font-semibold text-gray-700">Deskripsi</label>
                <textarea id="description" name="description" rows="4"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description', $status->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('ticket-status.show', $status->id) }}"
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
