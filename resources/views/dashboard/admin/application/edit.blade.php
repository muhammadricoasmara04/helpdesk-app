@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Edit Aplikasi</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <form action="{{ url('/dashboard/application/' . $application->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium">Nama Aplikasi</label>
                <input type="text" name="application_name"
                    value="{{ old('application_name', $application->application_name) }}"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="description" class="w-full border rounded-lg px-3 py-2" required>{{ old('description', $application->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Organization ID</label>
                <input type="text" name="organization_id"
                    value="{{ old('organization_id', $application->organization_id) }}"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ url('/dashboard/application/' . $application->id) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
@endsection
