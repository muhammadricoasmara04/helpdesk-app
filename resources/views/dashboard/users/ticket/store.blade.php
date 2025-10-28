@extends('layouts.main-dashboard')
@section('container')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow mt-10">
        <h2 class="text-xl font-semibold mb-4">Buat Tiket Pengaduan</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('ticket.store') }}" method="POST">
            @csrf

            {{-- Hidden employee id & name --}}
            <input type="hidden" name="employee_number" value="{{ auth()->id() }}">
            <input type="hidden" name="employee_name" value="{{ auth()->user()->name }}">

            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">Nama Karyawan</label>
                <input type="text" value="{{ auth()->user()->name }}" class="w-full border rounded p-2 bg-gray-100"
                    readonly>
            </div>

            <div class="grid grid-cols-2 gap-3">

                {{-- Prioritas --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Prioritas</label>
                    <select name="ticket_priority_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Prioritas --</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-3">
                {{-- Aplikasi --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Aplikasi</label>
                    <select name="application_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Aplikasi --</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}">{{ $app->application_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Jenis Masalah --}}
                <div>
                    <label class="block text-sm font-medium mb-1">Jenis Masalah</label>
                    <select name="application_problem_id" class="w-full border rounded p-2" required>
                        <option value="">-- Pilih Masalah --</option>
                        @foreach ($problems as $problem)
                            <option value="{{ $problem->id }}">{{ $problem->problem_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium mb-1">Subjek</label>
                <input type="text" name="subject" class="w-full border rounded p-2" required>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="description" class="w-full border rounded p-2" rows="4"></textarea>
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium mb-1">Nama Jabatan</label>
                <input type="text" name="position_name" class="w-full border rounded p-2">
            </div>

            <div class="mt-3">
                <label class="block text-sm font-medium mb-1">Nama Organisasi</label>
                <input type="text" name="organization_name" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-4">
                Kirim Tiket
            </button>
        </form>
    </div>
@endsection
