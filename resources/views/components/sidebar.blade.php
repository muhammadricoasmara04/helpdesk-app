<!-- Sidebar -->
<div id="sidebar"
    class="hidden lg:flex flex-col w-64 bg-linear-to-b from-gray-700 to-blue-500 rounded-r-lg h-screen overflow-y-auto transition-transform duration-300">
    {{-- Header --}}
    <div class="flex items-center justify-center py-6 border-b border-gray-700">
        <div class="flex items-center gap-2">
            <img src="/img/logo.png" alt="Logo" class="w-auto h-auto object-contain p-2">
        </div>
    </div>


    @php
        $user = session('user');
        $currentRoute = Route::currentRouteName();
    @endphp

    {{-- Navigation --}}
    <nav class="flex flex-col flex-1 overflow-y-auto px-3 py-6 gap-2">

        @if ($user['role'] === 'admin')
            <x-link route="dashboard"
                icon="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z">
                Dashboard
            </x-link>

            <x-link route="ticket.index"
                icon="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z">
                Ticket
            </x-link>

            <x-link route="application"
                icon="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3">
                Layanan
            </x-link>

            <x-link route="application-problems"
                icon="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14z">
                Masalah Layanan
            </x-link>

            <x-link route="ticket-status"
                icon="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7zm6 4l2 2 4-4">
                Tiket Status
            </x-link>

            <x-link route="ticket-priority"
                icon="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                Tiket Prioritas
            </x-link>

            <x-link route="users.index"
                icon="M5.121 17.804A7.975 7.975 0 0112 15c1.933 0 3.683.686 5.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z">
                Manajemen User
            </x-link>

            <x-link route="organization.index"
                icon="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4 0v-8m-6 8h12a2 2 0 002-2V10a2 2 0 00-.586-1.414l-7-7a2 2 0 00-2.828 0l-7 7A2 2 0 003 10v8a2 2 0 002 2z">
                Unit Kerja
            </x-link>
        @elseif($user['role'] === 'user')
            <x-link route="dashboard.user"
                icon="M5.121 17.804A7.975 7.975 0 0112 15c1.933 0 3.683.686 5.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z">
                Dashboard
            </x-link>

            <x-link route="user.ticket.index"
                icon="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z">
                Ticket
            </x-link>
        @else
            <x-link route="dashboard.staff"
                icon="M5.121 17.804A7.975 7.975 0 0112 15c1.933 0 3.683.686 5.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z">
                Dashboard
            </x-link>

            <x-link route="staff.ticket.index"
                icon="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z">
                Ticket
            </x-link>

            <x-link route="staff.application"
                icon="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3">
                Layanan
            </x-link>

            <x-link route="staff.application-problems"
                icon="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14z">
                Masalah Layanan
            </x-link>

            <x-link route="staff.ticket-status"
                icon="M2 7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7zm6 4l2 2 4-4">
                Tiket Status
            </x-link>

            <x-link route="staff.ticket-priority"
                icon="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z">
                Tiket Prioritas
            </x-link>

             <x-link route="staff.users.index"
                icon="M5.121 17.804A7.975 7.975 0 0112 15c1.933 0 3.683.686 5.121 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z">
                Manajemen User
            </x-link>

             <x-link route="staff.organization.index"
                icon="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4 0v-8m-6 8h12a2 2 0 002-2V10a2 2 0 00-.586-1.414l-7-7a2 2 0 00-2.828 0l-7 7A2 2 0 003 10v8a2 2 0 002 2z">
                Unit Kerja
            </x-link>
        @endif
    </nav>
</div>
