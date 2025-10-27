<aside class="w-64 bg-white border-r border-gray-200 flex flex-col">
    <div class="p-4 text-2xl font-bold text-indigo-600 border-b border-gray-200">
        My Dashboard
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-auto">
        @php $user = session('user'); @endphp

        @if ($user['role_id'] === 1)
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Dashboard</a>
            <a href="{{ route('application') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Aplikasi</a>
            <a href="{{ route('application-problems') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Masalah
                Aplikasi</a>
            <a href="{{ route('ticket-status') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Tiket
                Status</a>
            <a href="{{ route('ticket-priority') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Tiket
                Prioritas</a>
        @else
            <a href="{{ route('dashboard.user') }}" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Dashboard
                User</a>
            <a href="/my-tickets" class="block px-3 py-2 rounded-md hover:bg-indigo-100">Tiket Saya</a>
        @endif
    </nav>

    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 rounded-md hover:bg-red-100 text-red-600 font-semibold">
                Logout
            </button>
        </form>
    </div>
</aside>
