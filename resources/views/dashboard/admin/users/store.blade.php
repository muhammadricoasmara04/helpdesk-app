@extends('layouts.main-dashboard')

@section('container')
<h1 class="text-2xl font-bold mb-6">Tambah User Baru</h1>

<form action="{{ route('users.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-gray-700 mb-1">Nama</label>
        <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name') }}" required>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Email</label>
        <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email') }}" required>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Password</label>
        <input type="password" name="password" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Jabatan</label>
        <input type="text" name="position_name" class="w-full border rounded p-2" value="{{ old('position_name') }}">
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Role</label>
        <select name="role_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih Role --</option>
            @foreach ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Organisasi</label>
        <select name="organization_id" class="w-full border rounded p-2" required>
            <option value="">-- Pilih Organisasi --</option>
            @foreach ($organizations as $organization)
                <option value="{{ $organization->id }}">{{ $organization->organization }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        <a href="{{ route('users.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
    </div>
</form>
@endsection
