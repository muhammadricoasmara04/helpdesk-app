<header class="flex items-center justify-between bg-white border-b border-gray-200 px-6 h-16">
    <!-- Left Section: Sidebar Toggle & Title -->
    <div class="flex items-center space-x-4">
        <!-- Sidebar Toggle Button (Mobile/Tablet) -->
        <button id="sidebarToggle" class="text-gray-500 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>

        <!-- Page Title -->
        <h1 class="text-lg font-semibold text-gray-700">
            {{ $title ?? 'Dashboard' }}
        </h1>
    </div>

    <!-- Right Section: User Info -->
    <div class="flex items-center space-x-4">
        <div class="relative" id="userDropdown">
            <!-- User Button -->
            <button id="userDropdownButton" class="flex items-center space-x-2 focus:outline-none">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') }}"
                    alt="User Avatar" class="w-8 h-8 rounded-full object-cover">
                <span class="text-gray-700 font-medium">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div id="userDropdownMenu"
                class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-600">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
