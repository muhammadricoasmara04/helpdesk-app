@props(['route', 'icon'])
@php
$current = Route::currentRouteName();

$isActive =
    $current === $route || // kalau sama persis
    Str::startsWith($current, $route . '.') || // turunan pakai titik
    (
        Str::startsWith($current, $route . '-') &&
        !Str::startsWith($current, $route . '-problems') // hindari bentrok khusus problems
    );
@endphp

<a href="{{ route($route) }}"
    class="flex items-center px-4 py-2 text-sm font-medium rounded-2xl transition-all duration-150
        {{ $isActive ? 'bg-blue-600 text-white shadow-md' : 'text-gray-200 hover:bg-gray-600 hover:bg-opacity-25' }}">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"
        class="w-5 h-5 mr-3">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
    </svg>
    {{ $slot }}
</a>
