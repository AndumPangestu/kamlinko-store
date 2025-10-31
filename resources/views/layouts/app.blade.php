<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Kamlinko Admin Panel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased overflow-auto">
    <div class="min-h-screen bg-[#E6E6E6] dark:bg-gray-900">
        <div class="flex">
            <section class="w-[30%] lg:w-[20%] px-5">
                @include('layouts.navigation')
            </section>
            <section class="w-[70%] lg:w-[80%] bg-white px-5 pt-7 mt-5 rounded-tl-3xl shadow-2xl">
                <!-- Page Heading -->
                <nav class="font-poppins">
                    {{ $header ?? '' }}
                </nav>

                <div class="w-full grid grid-cols-2 pt-5 border-b border-gray-300 font-poppins">
                    <section class="pb-5">
                        <p class="text-gray-900 text-3xl font-semibold">
                            @isset($title)
                                {{ $title }}
                            @endisset
                        </p>
                        <p class="text-gray-400 text-lg">
                            @isset($headerDesc)
                                {{ $headerDesc }}
                            @endisset
                        </p>
                    </section>
                    <section class="flex flex-row justify-end items-center gap-5 ">
                        @if (isset($searchBar))
                            <!-- Search input -->
                            {!! $searchBar !!}
                        @endif
                        @if (isset($filter))
                            <!-- Filter -->
                            <span class="border border-gray-300 rounded-lg p-2 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" class="bi bi-filter text-gray-500" viewBox="0 0 16 16">
                                    <path
                                        d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                                </svg>
                            </span>
                        @endif
                        @if (isset($datePicker))
                            <!-- Date picker -->
                            <span class="border border-gray-300 rounded-lg p-2 w-fit cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    fill="currentColor" class="bi bi-calendar text-gray-500" viewBox="0 0 16 16">
                                    <path
                                        d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5M1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4z" />
                                </svg>
                            </span>
                        @endif
                    </section>
                </div>

                <!-- Page Content -->
                <main class="">
                    {{ $slot }}
                </main>
            </section>
        </div>
    </div>
</body>

</html>

<script>
    try {
        const searchElement = document.getElementById('searchIcon');
        searchElement.style.transform = 'translateY(-2rem)'
    } catch (error) {}
</script>
