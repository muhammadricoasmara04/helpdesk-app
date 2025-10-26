@extends('layouts.main-dashboard')

@section('container')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Detail Problem</h2>

        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-2xl font-bold mb-4">Detail Problem</h2>
            <p><strong>Nama Masalah:</strong> {{ $problem->problem_name }}</p>
            <p><strong>Deskripsi:</strong> {{ $problem->description }}</p>
            <p><strong>Aplikasi:</strong> {{ $problem->application->application_name ?? '-' }}</p>

            <div class="mt-4 space-x-2">
                <a href="{{ route('application-problem.edit', $problem->id) }}"
                    class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                    Edit
                </a>
                <a href="" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-gray-600">Kembali</a>
            </div>
        </div>
    @endsection
