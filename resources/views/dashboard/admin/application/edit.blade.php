@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Edit Layanan</h1>

    <div class="max-w-2xl bg-white rounded-2xl shadow p-6">
        <form action="{{ url('/dashboard/application/' . $application->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium">Nama Layanan</label>
                <input type="text" name="application_name"
                    value="{{ old('application_name', $application->application_name) }}"
                    class="w-full border rounded-lg px-3 py-2" required>
            </div>
            <div>
                <label for="application_code" class="block font-semibold mb-1">Layanan Code</label>
                <input id="application_code" name="application_code"
                    class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" rows="3"
                    value="{{ old('application_name', $application->application_code) }}"></input>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Deskripsi</label>
                <textarea name="description" class="w-full border rounded-lg px-3 py-2" required>{{ old('description', $application->description) }}</textarea>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ url('/dashboard/application/' . $application->id) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
            </div>
        </form>
    </div>
@endsection
