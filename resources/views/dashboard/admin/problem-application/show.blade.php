@extends('layouts.main-dashboard')

@section('container')
    <div class="min-h-screen bg-[#0f2d52] py-10 px-6">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-white mb-6 border-b-2 border-blue-300 pb-2">
                🧩 Detail Problem
            </h2>

            <div class="bg-white shadow-2xl rounded-2xl p-8 border border-gray-200">
                <div class="space-y-5 text-gray-700">
                    <div>
                        <h3 class="text-2xl font-semibold text-[#0f2d52]">{{ $problem->problem_name }}</h3>
                    </div>

                    <div class="border-t border-gray-300 pt-4 space-y-3">
                        <p><strong class="text-[#0f2d52]">Deskripsi:</strong> {{ $problem->description }}</p>
                        <p><strong class="text-[#0f2d52]">Aplikasi:</strong> {{ $problem->application->application_name ?? '-' }}</p>
                        <p><strong class="text-[#0f2d52]">Dibuat oleh:</strong> {{ $problem->creator->name ?? '-' }}</p>
                        <p><strong class="text-[#0f2d52]">Diperbarui oleh:</strong> {{ $problem->updater->name ?? '-' }}</p>
                    </div>

                    <div class="border-t border-gray-300 pt-4 text-sm text-gray-600">
                        <p><strong class="text-[#0f2d52]">Tanggal Dibuat:</strong>
                            {{ $problem->created_at ? $problem->created_at->format('d M Y, H:i') : '-' }}
                        </p>
                        <p><strong class="text-[#0f2d52]">Terakhir Diperbarui:</strong>
                            {{ $problem->updated_at ? $problem->updated_at->format('d M Y, H:i') : '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-end mt-8 space-x-3">
                    <a href="{{ route('application-problem.edit', $problem->id) }}"
                        class="bg-[#0f2d52] hover:bg-[#173d6d] text-white px-5 py-2 rounded-lg shadow-md transition duration-200">
                        ✏️ Edit
                    </a>
                    <a href="{{ route('application-problems') }}"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg shadow-md transition duration-200">
                        ↩️ Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
