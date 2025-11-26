<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</head>
<title>Help Desk</title>

<body class="overflow-x-hidden font-inter">

    {{-- @include('layout.partial.navbar') --}}

    @yield('container')

    {{-- @include('layout.partial.footer') --}}
    @stack('scripts')
    <div id="loading-spinner"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-[9999]">
        <div class="w-14 h-14 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
    </div>
</body>

</html>
