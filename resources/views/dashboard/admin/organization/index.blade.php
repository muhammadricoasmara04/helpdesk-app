@extends('layouts.main-dashboard')

@section('container')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Daftar Organisasi</h1>
        <a href="{{ route('organization.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition-colors">
            + Tambah Organisasi
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow border border-gray-200">
        <table class="min-w-full border-collapse text-sm">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="text-gray-700 text-left">
                    <th class="px-6 py-3 border-b font-semibold">No</th>
                    <th class="px-6 py-3 border-b font-semibold">Nama Organisasi</th>
                    <th class="px-6 py-3 border-b font-semibold">Status</th>
                    <th class="px-6 py-3 border-b font-semibold">Tanggal Dibuat</th>
                    <th class="px-6 py-3 border-b font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($organizations as $index => $org)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3 text-gray-700">{{ $index + 1 }}</td>
                        <td class="px-6 py-3 text-gray-700">{{ $org->organization }}</td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{ $org->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-700' }}">
                                {{ $org->status }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-600">{{ $org->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('organization.edit', $org->id) }}"
                                class="text-yellow-600 hover:underline font-medium">Edit</a>
                            |
                            <form action="{{ route('organization.destroy', $org->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus organisasi ini?')"
                                    class="text-red-600 hover:underline font-medium">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500">Belum ada data organisasi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $organizations->links() }}
    </div>
@endsection
