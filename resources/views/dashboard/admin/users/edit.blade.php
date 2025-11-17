@extends('layouts.main-dashboard')

@section('container')
    <h1 class="text-2xl font-bold mb-6">Edit User</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" class="w-full border rounded p-2" value="{{ old('name', $user->name) }}"
                required>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">NIP</label>
            <input type="text" name="nip" class="w-full border rounded p-2" value="{{ old('nip', $user->nip) }}"
                required>

        </div>

        <div>
            <label class="block text-gray-700 mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded p-2" value="{{ old('email', $user->email) }}"
                required>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Password (Opsional)</label>
            <input type="password" name="password" class="w-full border rounded p-2">
            <small class="text-gray-500">Kosongkan jika tidak ingin mengganti password.</small>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Jabatan</label>
            <input type="text" name="position_name" class="w-full border rounded p-2"
                value="{{ old('position_name', $user->position_name) }}">
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Role</label>
            <select name="role_id" class="w-full border rounded p-2" required>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-gray-700 mb-1">Organisasi</label>
            <select name="organization_id" class="w-full border rounded p-2" required>
                @foreach ($organizations as $organization)
                    <option value="{{ $organization->id }}">{{ $organization->organization }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Perbarui</button>
            <a href="{{ route('users.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
@endsection
