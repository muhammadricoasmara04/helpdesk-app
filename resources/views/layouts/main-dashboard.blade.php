<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>{{ $title ?? 'Dashboard' }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100 flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Header / Navbar --}}
        @include('components.header')

        {{-- Main Content --}}
        <main class="flex-1 overflow-auto p-6">
            @yield('container')
        </main>
        <div id="loading-spinner"
            class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[9999]">
            <div class="w-14 h-14 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
        </div>
        
    </div>
    @stack('scripts')
    <script>
        @if (session('api_token'))
            localStorage.setItem('token', "{{ session('api_token') }}");
        @endif
        @if (session('user'))
            localStorage.setItem('user', JSON.stringify(@json(session('user'))));
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const userButton = document.getElementById('userDropdownButton');
            const userMenu = document.getElementById('userDropdownMenu');

            // Toggle sidebar
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('hidden');
            });

            // Toggle user dropdown
            userButton.addEventListener('click', () => {
                userMenu.classList.toggle('hidden');
            });

            // Close dropdown if clicked outside
            document.addEventListener('click', (e) => {
                if (!userButton.contains(e.target) && !userMenu.contains(e.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
