@extends('layouts.main-dashboard')

@section('container')
    <div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8 flex justify-center">
        <div class="max-w-3xl w-full">
            {{-- Header --}}
            <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-2 border-blue-300 pb-3">
                🧩 Detail Problem
            </h2>

            {{-- Card --}}
            <div class="bg-white shadow-xl rounded-3xl p-8 border border-gray-200">
                <div class="space-y-6">

                    <!-- Problem Name -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <span class="text-gray-600 font-medium">Nama Problem</span>
                        <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $problem->problem_name }}</span>
                    </div>

                    <!-- Deskripsi -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-start bg-gray-50 p-4 rounded-lg shadow-sm">
                        <span class="text-gray-600 font-medium">Deskripsi</span>
                        <span class="text-gray-900 mt-2 sm:mt-0">{{ $problem->description }}</span>
                    </div>

                    <!-- Aplikasi -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <span class="text-gray-600 font-medium">Aplikasi</span>
                        <span
                            class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $problem->application->application_name ?? '-' }}</span>
                    </div>

                    <!-- Dibuat & Diperbarui oleh -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <span class="text-gray-600 font-medium">Dibuat oleh</span>
                        <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $problem->creator->name ?? '-' }}</span>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                        <span class="text-gray-600 font-medium">Diperbarui oleh</span>
                        <span class="text-gray-900 font-semibold mt-2 sm:mt-0">{{ $problem->updater->name ?? '-' }}</span>
                    </div>

                    <!-- Tanggal Dibuat & Terakhir Diperbarui -->
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm text-gray-600 text-sm">
                        <span class="font-medium">Tanggal Dibuat</span>
                        <span
                            class="mt-1 sm:mt-0">{{ $problem->created_at ? $problem->created_at->format('d M Y, H:i') : '-' }}</span>
                    </div>
                    <div
                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-gray-50 p-4 rounded-lg shadow-sm text-gray-600 text-sm">
                        <span class="font-medium">Terakhir Diperbarui</span>
                        <span
                            class="mt-1 sm:mt-0">{{ $problem->updated_at ? $problem->updated_at->format('d M Y, H:i') : '-' }}</span>
                    </div>

                </div>

                <!-- Buttons -->
                {{-- <div class="flex flex-col sm:flex-row justify-end mt-8 gap-3">
                    <a href="{{ route('application-problem.edit', $problem->id) }}"
                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-md transition">
                        ✏️ Edit
                    </a>
                    <a href="{{ route('application-problems') }}"
                        class="px-5 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl font-medium shadow-md transition">
                        ↩️ Kembali
                    </a>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
