@extends('layouts.main-dashboard')
@section('container')
    @php
        $maxFiles = 5;
        $used = $ticket->attachments->count();
        $remaining = $maxFiles - $used;

        $statusColors = [
            'Open' => 'bg-blue-200 text-blue-800',
            'In Progress' => 'bg-yellow-200 text-yellow-800',
            'Pending' => 'bg-gray-200 text-gray-800',
            'Closed' => 'bg-green-200 text-green-800',
        ];

        $statusColor = $statusColors[$ticket->status->name] ?? 'bg-gray-200 text-gray-800';

        $priorityColors = [
            'Low' => 'bg-green-200 text-green-800',
            'Medium' => 'bg-yellow-200 text-yellow-800',
            'High' => 'bg-red-200 text-red-800',
        ];

        $priorityColor = $priorityColors[$ticket->priority->name ?? ''] ?? 'bg-gray-200 text-gray-800';
    @endphp
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 border border-green-200">
            ✅ {{ session('success') }}
        </div>
    @endif
    <div class="max-w-6xl mx-auto mt-2">
        <div class="flex h-[600px] gap-x-6">

            {{-- Chat Section --}}
            <div id="ticket-header" data-status="{{ $ticket->status }}"
                class="flex-1 flex flex-col border-r border-gray-200 rounded-lg shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">💬Chat</h2>
                    <span
                        class="assign text-sm bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Menunggu' }}</span>
                    <button id="end-chat-btn"
                        class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-full shadow-md transition-all duration-200 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Akhiri Tiket
                    </button>

                    <span id="chat-status-label"
                        class="bg-green-500 text-white text-xs px-3 py-1.5 rounded-full shadow-md hidden">
                        Tiket Ditutup
                    </span>
                </div>

                {{-- Chat Box --}}
                <div id="chat-box" class="flex-1 p-5 space-y-4 overflow-y-auto bg-gray-50"
                    style="scroll-behavior: smooth;">
                    <!-- Pesan akan ditampilkan di sini -->
                </div>

                {{-- Footer / Input Form --}}
                <div class="border-t bg-white px-4 py-3">
                    <form id="chat-form" class="flex items-center space-x-2">
                        <input type="hidden" id="ticket_id" value="{{ $ticket->id }}">

                        <input type="text" id="message" placeholder="Ketik pesan..."
                            class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>

                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-full transition-all duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                            </svg>

                        </button>
                    </form>
                </div>
            </div>

            {{-- Ticket Description / Info Sidebar --}}
            <div
                class="w-80 bg-linear-to-b from-white to-gray-50 border border-gray-200 p-6 flex flex-col rounded-2xl shadow-lg overflow-y-auto space-y-6 transition-all duration-300 hover:shadow-xl">

                <!-- Header -->
                <div class="text-center border-b pb-3">
                    <h1 class="font-bold text-2xl text-gray-800 tracking-tight mb-1">🎟️ Ticket Detail</h1>
                    <p class="text-xs text-gray-500">Informasi lengkap tiket pengguna</p>
                </div>

                <!-- Info Utama -->
                <div class="text-sm text-gray-700 grid grid-cols-2 gap-y-3 gap-x-2">
                    <div class="font-semibold text-gray-600">Ticket</div>
                    <div class="text-gray-900">{{ $ticket->ticket_code }}</div>

                    <div class="font-semibold text-gray-600">Client</div>
                    <div class="text-gray-900">{{ $ticket->employee_name }}</div>

                    <div class="font-semibold text-gray-600">Layanan</div>
                    <div class="text-gray-900">{{ $ticket->application->application_name }}</div>

                    <div class="font-semibold text-gray-600">Assigned</div>
                    <div class="text-gray-900">
                        {{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Belum ditugaskan' }}
                    </div>

                    <div class="font-semibold text-gray-600">Status</div> <span
                        class="{{ $statusColor }} px-2 py-1 rounded-full text-xs mb-3 inline-block w-fit">
                        {{ $ticket->status->name }} </span>
                    {{-- <div class="font-semibold text-gray-600">Priority</div> <span
                        class="{{ $priorityColor }} px-2 py-1 rounded-full text-xs mb-3 inline-block w-fit">
                        {{ $ticket->priority->name }} </span> --}}

                    <div class="font-semibold text-gray-600">Created At</div>
                    <div class="text-gray-900">
                        {{ $ticket->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>

                    <div class="font-semibold text-gray-600">Updated At</div>
                    <div class="text-gray-900">
                        {{ $ticket->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>

                    <div class="font-semibold text-gray-600">Deskripsi</div>
                    <div class="text-gray-800 italic leading-relaxed">
                        {{ $ticket->subject }}
                    </div>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between mb-3 border-b pb-1">
                        <h2 class="font-semibold text-gray-700 flex items-center gap-2">
                            📎 Lampiran
                        </h2>

                        <label for="attachment-upload"
                            class="flex items-center gap-1 text-blue-600 text-sm font-medium cursor-pointer hover:text-blue-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah
                        </label>

                        <input type="file" id="attachment-upload" name="attachments[]" class="hidden" multiple
                            accept=".jpg,.jpeg,.png,.pdf">
                    </div>

                    <div class="text-sm text-gray-600 mt-2">
                        <span class="font-semibold">{{ $used }}</span> dari
                        <span class="font-semibold">{{ $maxFiles }}</span> lampiran terpakai
                        @if ($remaining > 0)
                            <span class="text-green-600">(sisa {{ $remaining }} lagi)</span>
                        @else
                            <span class="text-red-600">(limit tercapai)</span>
                        @endif
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
<script>
    window.existingAttachments = {{ $ticket->attachments->count() }};
</script>
@push('scripts')
    @vite('resources/js/dashboard-user/ticket-replied.js')
@endpush
