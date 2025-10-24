<header class="flex items-center justify-between bg-white border-b border-gray-200 px-6 h-16">
    <div class="text-lg font-semibold text-gray-700">
        {{ $title ?? 'Dashboard' }}
    </div>

    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-2">
            <img src="{{ Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/32' }}" 
                 alt="User Avatar"
                 class="w-8 h-8 rounded-full object-cover" />
            <span class="text-gray-700 font-medium">{{ Auth::user()->name ?? 'User' }}</span>
        </div>
    </div>
</header>
