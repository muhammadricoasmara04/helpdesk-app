@extends('layouts.main-dashboard')

@section('container')
    @php
        $statusColors = [
            'Open' => 'bg-blue-200 text-blue-800',
            'In Progress' => 'bg-yellow-200 text-yellow-800',
            'Closed' => 'bg-red-200 text-red-800',
        ];

        $statusColor = $statusColors[$ticket->status->name] ?? 'bg-gray-200 text-gray-800';

        $priorityColors = [
            'Low' => 'bg-green-200 text-green-800',
            'Medium' => 'bg-yellow-200 text-yellow-800',
            'High' => 'bg-red-200 text-red-800',
        ];

        $priorityColor = $priorityColors[$ticket->priority->name ?? ''] ?? 'bg-gray-200 text-gray-800';
    @endphp
    <div class="max-w-6xl mx-auto mt-2">
        <div class="flex h-[600px] gap-x-6">

            {{-- Chat Section --}}
            <div class="flex-1 flex flex-col border-r border-gray-200 rounded-lg shadow-sm overflow-hidden">
                {{-- Header --}}
                <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">💬 Live Chat Tiket</h2>
                    <span
                        class="text-sm opacity-90">{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Menunggu' }}</span>
                </div>

                {{-- Chat Box --}}
                <div id="chat-box" class="flex-1 p-5 space-y-4 overflow-y-auto bg-gray-50" style="scroll-behavior: smooth;">
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
                            Kirim
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ticket Description / Info Sidebar --}}
            <div class="w-80 bg-gray-100 p-5 flex flex-col rounded-lg shadow-sm overflow-y-auto space-y-6">
                <h1 class="font-bold text-3xl">Ticket Detail</h1>

                <div class="mt-4 text-sm text-gray-700 grid grid-cols-2 gap-y-2 space-y-4">
                    <div class="font-semibold text-gray-600">Ticket</div>
                    <div>{{ $ticket->ticket_code }}</div>

                    <div class="font-semibold text-gray-600">Client</div>
                    <div>{{ $ticket->employee_name }}</div>

                    <div class="font-semibold text-gray-600">Aplikasi</div>
                    <div>{{ $ticket->application->application_name }}</div>

                    <div class="font-semibold text-gray-600">Assigned</div>
                    <div>{{ $ticket->assignedTo ? $ticket->assignedTo->name : 'Belum ditugaskan' }}</div>

                    <div class="font-semibold text-gray-600">Status</div>
                    <span class="{{ $statusColor }} px-2 py-1 rounded-full text-xs mb-3 inline-block">
                        {{ $ticket->status->name }}
                    </span>

                    <div class="font-semibold text-gray-600">Priority</div>
                    <span class="{{ $priorityColor }} px-2 py-1 rounded-full text-xs mb-3 inline-block">
                        {{ $ticket->priority->name }}
                    </span>

                    <div class="font-semibold text-gray-600">Created At</div>
                    <div class="text-sm text-gray-700 mb-3">
                        {{ $ticket->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>

                    <div class="font-semibold text-gray-600">Deskripsi</div>
                    <div class="text-sm text-gray-700 mb-3">
                        {{ $ticket->subject }}
                    </div>

                </div>

                <div class="mt-4">
                    <h2 class="font-semibold text-gray-600 mb-2">Lampiran</h2>
                    @if ($ticket->attachments->isNotEmpty())
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            @foreach ($ticket->attachments as $attachment)
                                @php
                                    $filePath = asset('storage/' . $attachment->file_path);
                                    $extension = pathinfo($attachment->file_path, PATHINFO_EXTENSION);
                                @endphp

                                @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png']))
                                    <div class="border rounded p-2">
                                        <a href="{{ $filePath }}" target="_blank">
                                            <img src="{{ $filePath }}" alt="Lampiran"
                                                class="w-full h-auto object-cover rounded cursor-pointer transform hover:scale-105 transition duration-200">
                                        </a>
                                    </div>
                                @else
                                    <div class="border rounded p-2 flex items-center justify-between">
                                        <span>{{ basename($attachment->file_path) }}</span>
                                        <a href="{{ $filePath }}" target="_blank"
                                            class="text-blue-600 hover:underline">Download</a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif


                </div>

            </div>

        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard-user/ticket-replied.js')
@endpush
