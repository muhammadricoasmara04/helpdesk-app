@extends('layouts.main-dashboard')

@section('container')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar User</h1>
        <a href="{{ route('users.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors">
            + Tambah User Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
        <table class="min-w-full border-collapse text-sm">
            <thead class="bg-gray-50">
                <tr class="text-gray-700 text-left">
                    <th class="px-6 py-3 border-b font-semibold">No</th>
                    <th class="px-6 py-3 border-b font-semibold">Nama</th>
                    <th class="px-6 py-3 border-b font-semibold">Email</th>
                    <th class="px-6 py-3 border-b font-semibold">Role</th>
                    <th class="px-6 py-3 border-b font-semibold">Organisasi</th>
                    <th class="px-6 py-3 border-b font-semibold">Tanggal Dibuat</th>
                    <th class="px-6 py-3 border-b font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3">{{ $user->name }}</td>
                        <td class="px-6 py-3">{{ $user->email }}</td>
                        <td class="px-6 py-3">{{ $user->role->name ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $user->organization->organization ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 space-x-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus user ini?')"
                                    class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">Belum ada user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
@endsection
