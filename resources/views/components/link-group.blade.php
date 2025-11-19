@props([
    'title',
    'icon', // svg path d="..."
    'routes' => [], // array route names that should make this group auto-open/active
])

@php
    $current = Route::currentRouteName();
    $isActiveParent = in_array($current, $routes);
@endphp

<details class="group" @if ($isActiveParent) open @endif>
    <summary
        class="cursor-pointer flex items-center justify-between gap-3 px-4 py-2 rounded-2xl transition-all duration-150
            {{ $isActiveParent ? 'bg-blue-600 text-white shadow-md' : 'text-gray-200 hover:bg-gray-600 hover:bg-opacity-25' }}">

        <!-- left: icon + title -->
        <div class="flex items-center gap-3">
            <!-- left icon (tidak berputar) -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>


            <!-- title text -->
            <span class="select-none">{{ $title }}</span>
        </div>

        <!-- right: dropdown chevron (akan berputar saat details[open]) -->
        <svg class="w-4 h-4 transition-transform group-open:rotate-90 flex-shrink-0" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </summary>

    <!-- submenu -->
    <div class="ml-8 mt-2 mb-2 space-y-1">
        {{ $slot }}
    </div>
</details>
