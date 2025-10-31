<aside class="w-64 bg-white border-r border-gray-200 flex flex-col shadow-lg">
    <!-- Header -->
    <div class="p-5 text-2xl font-bold text-indigo-600 border-b border-gray-200">
        My Dashboard
    </div>

    @php
        $user = session('user');
        $currentRoute = Route::currentRouteName();
    @endphp

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-auto">
        @if ($user['role'] === 'admin')
            <a href="{{ route('dashboard') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200 
               {{ $currentRoute === 'dashboard' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Dashboard
            </a>
            <a href="{{ route('application') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200
               {{ $currentRoute === 'application' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Aplikasi
            </a>
            <a href="{{ route('application-problems') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200
               {{ $currentRoute === 'application-problems' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Masalah Aplikasi
            </a>
            <a href="{{ route('ticket-status') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200
               {{ $currentRoute === 'ticket-status' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Tiket Status
            </a>
            <a href="{{ route('ticket-priority') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200
               {{ $currentRoute === 'ticket-priority' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Tiket Prioritas
            </a>
        @else
            <a href="{{ route('dashboard.user') }}"
                class="block px-3 py-2 rounded-md transition-colors duration-200
               {{ $currentRoute === 'dashboard.user' ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-indigo-100 hover:text-indigo-700' }}">
                Dashboard User
            </a>
        @endif
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-3 py-2 rounded-md hover:bg-red-100 text-red-600 font-semibold transition-colors duration-200">
                Logout
            </button>
        </form>

        <div class="mt-4 text-gray-700">
            <p class="font-medium">Selamat datang, {{ $user['name'] }}</p>
            <p class="text-sm text-gray-500">Role ID: {{ $user['role_id'] }}</p>
        </div>
    </div>
</aside>
