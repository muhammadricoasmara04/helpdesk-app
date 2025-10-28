@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Tambah Aplikasi Baru</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <form id="applicationForm" class="space-y-4">
            <div>
                <label for="application_name" class="block font-semibold mb-1">Nama Aplikasi</label>
                <input type="text" id="application_name" name="application_name"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" placeholder="Masukkan nama aplikasi"
                    required>
            </div>
            <div>
                <label for="application_code" class="block font-semibold mb-1">Aplikasi Code</label>
                <input id="application_code" name="application_code" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    rows="3" placeholder="Contoh: SM, SA, TS, AS" required></input>
            </div>

            <div>
                <label for="description" class="block font-semibold mb-1">Deskripsi</label>
                <textarea id="description" name="description" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200"
                    rows="3" placeholder="Masukkan deskripsi aplikasi" required></textarea>
            </div>

            <div>
                <label for="organization_id" class="block font-semibold mb-1">Organization ID</label>
                <input type="text" id="organization_id" name="organization_id"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" placeholder="UUID organisasi"
                    required>
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
    @vite('resources/js/dashboard/application.js')
@endpush
