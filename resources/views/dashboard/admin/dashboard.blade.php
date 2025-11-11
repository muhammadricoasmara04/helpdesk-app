@extends('layouts.main-dashboard')

@section('container')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Tiket</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">

            <!-- Total Tickets -->
            <div
                class="flex items-center p-5 bg-linear-to-br from-blue-500 to-indigo-600 text-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-3xl font-bold">{{ $totalTickets }}</h4>
                    <p class="text-sm opacity-90">Total Tickets</p>
                </div>
            </div>

            <!-- Open Tickets -->
            <div
                class="flex items-center p-5 bg-linear-to-br from-green-500 to-emerald-600 text-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-3xl font-bold">{{ $openTickets }}</h4>
                    <p class="text-sm opacity-90">Open Tickets</p>
                </div>
            </div>

            <!-- On Progress Tickets -->
            <div
                class="flex items-center p-5 bg-linear-to-br from-yellow-400 to-amber-500 text-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-3xl font-bold">{{ $onProgressTickets }}</h4>
                    <p class="text-sm opacity-90">In Progress Ticket</p>
                </div>
            </div>

            <!-- Closed Tickets -->
            <div
                class="flex items-center p-5 bg-linear-to-br from-red-500 to-rose-600 text-white rounded-xl shadow-md hover:shadow-lg transition-shadow">
                <div class="p-3 bg-white/20 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-7 h-7">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h4 class="text-3xl font-bold">{{ $closedTickets }}</h4>
                    <p class="text-sm opacity-90">Closed Tickets</p>
                </div>
            </div>

        </div>

        <div class="mt-10 bg-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">
                    Analisis Tiket Closed per Bulan ({{ $year }})
                </h2>

                <form method="GET" action="{{ route('dashboard') }}">
                    <select name="year" onchange="this.form.submit()" class="border rounded-lg px-3 py-1 text-gray-700">
                        @for ($y = now()->year; $y >= now()->year - 3; $y--)
                            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </form>
            </div>

            <div class="flex justify-center">
                <div class="w-full md:w-[900px] lg:w-[1100px] xl:w-[1300px]">
                    <canvas id="ticketChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ticketChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'Closed Tickets',
                    data: @json($closedTicketsPerMonth),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#4b5563'
                        },
                        grid: {
                            color: 'rgba(229, 231, 235, 0.5)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#4b5563'
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Tiket Closed per Bulan',
                        font: {
                            size: 16,
                            weight: '600'
                        },
                        color: '#374151'
                    }
                }
            }
        });
    </script>
@endsection
