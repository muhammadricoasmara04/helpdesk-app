@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg mt-10 border border-gray-100">
        <h2 class="text-2xl font-semibold mb-2 text-gray-800">Buat Tiket Pengaduan</h2>
        <p class="text-sm text-gray-500 mb-6">
            Isi form di bawah untuk melaporkan kendala atau permintaan bantuan terkait aplikasi yang kamu gunakan.
        </p>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4 border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('user.ticket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <!-- Hidden employee data -->
            <input type="hidden" name="employee_number" value="{{ auth()->id() }}">
            <input type="hidden" name="employee_name" value="{{ auth()->user()->name }}">
            <input type="hidden" name="position_name" value="{{ auth()->user()->position_name }}">

            <!-- Data Pengguna -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">
                        Nama Karyawan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" value="{{ auth()->user()->name }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-gray-700" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">
                        Jabatan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="position_name" value="{{ auth()->user()->position_name }}" 
                        class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-gray-700" readonly>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">
                    Unit Kerja <span class="text-red-500">*</span>
                </label>
                <input type="text" name="organization_name" value="{{ old('organization', $organization) }}"
                    class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 text-gray-700" readonly>
            </div>

            <!-- Aplikasi & Masalah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">
                        Aplikasi <span class="text-red-500">*</span>
                    </label>
                    <select id="application_id" name="application_id"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring focus:ring-blue-100 focus:border-blue-400"
                        required>
                        <option value="">-- Pilih Aplikasi --</option>
                        @foreach ($applications as $app)
                            <option value="{{ $app->id }}">{{ $app->application_name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih aplikasi yang mengalami kendala.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1 text-gray-700">
                        Jenis Masalah <span class="text-red-500">*</span>
                    </label>
                    <select id="application_problem_id" name="application_problem_id"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring focus:ring-blue-100 focus:border-blue-400"
                        required>
                        <option value="">-- Pilih Masalah --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Jenis masalah akan menyesuaikan dengan aplikasi yang kamu pilih.</p>
                </div>
            </div>

            <!-- Prioritas -->
            <div class="hidden">
                <label class="block text-sm font-medium mb-1 text-gray-700">
                    Prioritas <span class="text-red-500">*</span>
                </label>
                <select id="ticket_priority_id"
                    class="w-full border border-gray-300 rounded-lg p-2.5 bg-gray-100 cursor-not-allowed text-gray-500"
                    disabled>
                    <option value="">-- Prioritas akan terisi otomatis --</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="ticket_priority_id" id="ticket_priority_hidden">
            </div>

            <!-- Subjek & Deskripsi -->
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">
                    Subjek <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" placeholder="Contoh: Tidak bisa login"
                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring focus:ring-blue-100 focus:border-blue-400"
                    required>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" placeholder="Jelaskan secara detail kendala yang kamu alami..."
                    class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring focus:ring-blue-100 focus:border-blue-400"
                    required></textarea>
            </div>

            <!-- Lampiran -->
            <div>
                <label class="block text-sm font-medium mb-1 text-gray-700">
                    Bukti Masalah <span class="text-red-500">*</span>
                </label>
                <input type="file" name="attachments[]" multiple
                    class="w-full border border-gray-300 rounded-lg p-2.5 cursor-pointer bg-gray-50 hover:bg-gray-100 transition">
                <p class="text-xs text-gray-500 mt-1">
                    Kamu bisa mengunggah beberapa file (JPG, PNG, PDF, DOCX). Maks 5MB per file.
                </p>
            </div>

            <!-- Tombol Kirim -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-200">
                     Kirim Tiket
                </button>
            </div>
        </form>
    </div>

    <script>
        const problems = @json($problems);
        const appSelect = document.getElementById('application_id');
        const problemSelect = document.getElementById('application_problem_id');
        const prioritySelect = document.getElementById('ticket_priority_id');
        const priorityHidden = document.getElementById('ticket_priority_hidden');

        // Saat aplikasi berubah → tampilkan problem yang relevan
        appSelect.addEventListener('change', function() {
            const selectedAppId = this.value;
            problemSelect.innerHTML = '<option value="">-- Pilih Masalah --</option>';

            const filteredProblems = problems.filter(p => p.application_id == selectedAppId);

            filteredProblems.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.problem_name;
                problemSelect.appendChild(opt);
            });

            // Reset prioritas
            prioritySelect.value = '';
            priorityHidden.value = '';
        });

        // Saat masalah dipilih → isi prioritas otomatis
        problemSelect.addEventListener('change', function() {
            const selectedProblemId = this.value;
            const selectedProblem = problems.find(p => p.id == selectedProblemId);

            if (selectedProblem && selectedProblem.ticket_priority_id) {
                prioritySelect.value = selectedProblem.ticket_priority_id;
                priorityHidden.value = selectedProblem.ticket_priority_id;
            } else {
                prioritySelect.value = '';
                priorityHidden.value = '';
            }
        });
    </script>
@endsection
