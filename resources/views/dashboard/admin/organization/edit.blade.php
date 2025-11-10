@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Organisasi</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6 border border-gray-200">
        <form action="{{ route('organization.update', $organization->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="organization" class="block font-semibold mb-1 text-gray-700">Nama Organisasi</label>
                <input type="text" id="organization" name="organization" value="{{ $organization->organization }}"
                    required class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>

            <div>
                <label for="status" class="block font-semibold mb-1 text-gray-700">Status</label>
                <select id="status" name="status"
                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring focus:ring-blue-200">
                    <option value="Aktif" {{ $organization->status === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Nonaktif" {{ $organization->status === 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
                    Perbarui
                </button>
            </div>
        </form>
    </div>
@endsection
