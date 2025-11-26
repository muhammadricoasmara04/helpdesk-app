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
            <div id="ticket-header" data-status="{{ $ticket->status }}"
                class="flex-1 flex flex-col border border-gray-200 rounded-2xl shadow-md overflow-hidden bg-white">
                {{-- Header --}}
                <div class="bg-linear-to-r from-blue-600 to-blue-500 text-white px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold flex items-center gap-2">
                        💬 <span>Chat</span>
                    </h2>
                    <span class="text-sm bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                        {{ $ticket->employee_name }}
                    </span>


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
                <div id="chat-box"
                    class="flex-1 p-6 space-y-4 overflow-y-auto bg-linear-to-b from-gray-50 to-gray-100 scroll-smooth">
                    @foreach ($ticket->replies as $msg)
                        @php
                            $isAdmin = $msg->user->role === 'admin';
                        @endphp
                        <div class="flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="relative px-4 py-3 rounded-2xl shadow-sm max-w-[70%] {{ $isAdmin ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 rounded-bl-none' }}">
                                {{-- Nama Pengirim --}}
                                <p class="text-xs mb-1 font-medium {{ $isAdmin ? 'text-blue-100' : 'text-gray-600' }}">
                                    {{ $msg->user->name }}
                                </p>

                                {{-- Isi Pesan --}}
                                <p class="text-sm leading-relaxed wrap-break-word">
                                    {{ $msg->message }}
                                </p>

                                {{-- Timestamp --}}
                                <p class="text-[11px] mt-1 opacity-70 {{ $isAdmin ? 'text-blue-100' : 'text-gray-500' }}">
                                    {{ $msg->created_at->timezone('Asia/Jakarta')->format('H:i, d M Y') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Footer / Input Form --}}
                <div class="border-t bg-white px-5 py-4">
                    <form id="chat-form" class="flex items-center gap-3">
                        <input type="hidden" id="ticket_id" value="{{ $ticket->id }}">
                        <input type="text" id="message" placeholder="Ketik pesan..."
                            class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-400 transition-all"
                            required>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full shadow-md transition-transform transform hover:scale-105 active:scale-95">
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

                    <div class="font-semibold text-gray-600">Created At</div>
                    <div class="text-gray-900">
                        {{ $ticket->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>

                    <div class="font-semibold text-gray-600">Created At</div>
                    <div class="text-gray-900">
                        {{ $ticket->updated_at->timezone('Asia/Jakarta')->translatedFormat('d F Y') }}
                    </div>

                    <div class="font-semibold text-gray-600">Deskripsi</div>
                    <div class="text-gray-800 italic leading-relaxed">
                        {{ $ticket->subject }}
                    </div>
                </div>

                <form action="{{ route('tickets.updatePriority', $ticket->id) }}" method="POST"
                    class="flex flex-col gap-3">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="font-semibold text-gray-600">Status</label>
                        <select name="ticket_status_id"
                            class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->id }}"
                                    {{ $ticket->ticket_status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hidden">
                        <label class="font-semibold text-gray-600">Priority</label>
                        <select name="ticket_priority_id"
                            class="border border-gray-300 rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none w-full">
                            @foreach ($priorities as $priority)
                                <option value="{{ $priority->id }}"
                                    {{ $ticket->ticket_priority_id == $priority->id ? 'selected' : '' }}>
                                    {{ $priority->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="mt-2 inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg shadow-md transition">
                        Update Status 
                    </button>
                    @if (session('success'))
                        <div class="mt-2 text-green-600 text-sm font-medium">
                            ✅ {{ session('success') }}
                        </div>
                    @endif
                </form>

                
            </div>


        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/dashboard/ticket-replied-admin.js')
@endpush
