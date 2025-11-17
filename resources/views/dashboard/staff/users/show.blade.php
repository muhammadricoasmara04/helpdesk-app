@extends('layouts.main-dashboard')

@section('container')
<div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8 flex justify-center">
    <div class="max-w-2xl w-full">

        {{-- Header --}}
        <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-blue-300 pb-3">
            👤 Detail User
        </h1>

        {{-- Card --}}
        <div class="bg-white shadow-xl rounded-3xl p-8 border border-gray-200 space-y-6">

            {{-- Nama --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Nama</span>
                <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $user->name }}</span>
            </div>

            {{-- Email --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Email</span>
                <span class="text-gray-900 mt-2 sm:mt-0">{{ $user->email }}</span>
            </div>

            {{-- Role --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Role</span>
                <span class="text-gray-900 mt-2 sm:mt-0">{{ $user->role->name ?? '-' }}</span>
            </div>

            {{-- Unit Kerja --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Unit Kerja</span>
                <span class="text-gray-900 mt-2 sm:mt-0">{{ $user->organization->organization ?? '-' }}</span>
            </div>

            {{-- Tanggal Dibuat --}}
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                <span class="text-gray-600 font-medium">Tanggal Dibuat</span>
                <span class="text-gray-900 mt-2 sm:mt-0">{{ $user->created_at->format('d M Y') }}</span>
            </div>

            {{-- Button --}}
            <div class="flex justify-end mt-6">
                <a href="{{ route('staff.users.index') }}"
                   class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium shadow-md transition">
                    ↩️ Kembali
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
