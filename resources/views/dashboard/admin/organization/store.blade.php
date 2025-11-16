@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Unit Kerja</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6 border border-gray-200">
        <form action="{{ route('organization.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="organization" class="block font-semibold mb-1 text-gray-700">Nama Unit Kerja</label>
                <input type="text" id="organization" name="organization" required
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200"
                    placeholder="Masukkan nama organisasi">
            </div>

            <div>
                <label for="status" class="block font-semibold mb-1 text-gray-700">Status</label>
                <select id="status" name="status"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                    <option value="Aktif">Aktif</option>
                    <option value="Nonaktif">Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection
