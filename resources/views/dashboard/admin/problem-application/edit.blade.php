@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 mt-10">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6 border-b pb-2">✏️ Edit Problem</h2>

        <form action="{{ route('application-problem.update', $problem->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Application -->
            <div>
                <label for="application_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Aplikasi
                </label>
                <select name="application_id" id="application_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @foreach ($applications as $app)
                        <option value="{{ $app->id }}" {{ $app->id == $problem->application_id ? 'selected' : '' }}>
                            {{ $app->application_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Problem Name -->
            <div>
                <label for="problem_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Nama Problem
                </label>
                <input type="text" name="problem_name" id="problem_name"
                    value="{{ old('problem_name', $problem->problem_name) }}"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Masukkan nama problem...">
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Deskripsi
                </label>
                <textarea name="description" id="description" rows="4"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Tuliskan deskripsi masalah...">{{ old('description', $problem->description) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ url()->previous() }}"
                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-100 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
@endsection
