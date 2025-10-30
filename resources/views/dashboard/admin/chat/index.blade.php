@extends('layouts.main-dashboard')

@section('container')
    <div class="max-w-3xl mx-auto mt-10">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden flex flex-col h-[600px]">

            {{-- Header --}}
            <div class="bg-blue-600 text-white px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">💬 Live Chat Tiket</h2>
                <span class="text-sm opacity-90">#{{ $ticket->id }}</span>
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
    </div>
@endsection
@push('scripts')
    @vite('resources/js/dashboard/ticket-replied-admin.js')
@endpush
