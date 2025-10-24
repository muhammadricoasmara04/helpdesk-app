@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Tambah Problem Aplikasi</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <form id="applicationProblemForm" class="space-y-4">
            <div>
                <label for="problem_name" class="block font-semibold mb-1">Nama Problem</label>
                <input type="text" id="problem_name" name="problem_name"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nama problem aplikasi" required>
            </div>

            <div>
                <label for="description" class="block font-semibold mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    rows="3" placeholder="Masukkan deskripsi problem" required></textarea>
            </div>

            <div>
                <label for="application_id" class="block font-semibold mb-1">Application ID</label>
                <input type="text" id="application_id" name="application_id"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" placeholder="UUID aplikasi terkait"
                    required>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                    Simpan
                </button>
            </div>
        </form>

        <p id="responseMessage" class="mt-4 text-sm text-center"></p>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/application-problem.js')
@endpush
