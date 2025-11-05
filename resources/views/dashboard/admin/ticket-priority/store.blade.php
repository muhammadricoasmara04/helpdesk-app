@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Tambah Ticket Priority</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <form id="ticketPriorityForm" class="space-y-4">
            <div>
                <label for="name" class="block font-semibold mb-1">Nama Prioritas</label>
                <input type="text" id="name" name="name"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    placeholder="Contoh: High, Medium, Low" required>
            </div>

            <div>
                <label for="description" class="block font-semibold mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    rows="3" placeholder="Masukkan deskripsi singkat" required></textarea>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">Simpan</button>
            </div>
        </form>

        <p id="responseMessage" class="mt-4 text-sm text-center"></p>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/ticket-priority.js')
@endpush
