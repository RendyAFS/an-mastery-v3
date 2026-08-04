<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'AN Mastery' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo-AnMastery.ico') }}">

    {{-- PWA Head Meta & Links --}}
    <meta name="theme-color" content="#4f46e5">
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/pwa-192x192.png') }}">

    {{-- Icon Library (defer agar tidak blokir render, tapi tetap diparse lebih awal) --}}
    <script src="{{ asset('js/luicide-latest.js') }}" defer></script>

    {{-- Init Theme --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    {{-- styles --}}
    <link rel="stylesheet" href="{{ asset('css/dataTables.min.css') }}">

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body>
    {{-- <x-custom-toast top="top-22" right="right-4" align="align-end" /> --}}
    <x-custom-alert top="top-22" right="right-4" align="align-end" />
    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Main Content Area --}}
    <div class="transition-all duration-300 lg:ms-64 hs-overlay-minified:lg:ms-14">


        <div class="min-h-screen flex flex-col">
            <x-navbar />

            <main
                class="flex-1 px-10 py-8
                bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                text-(--color-dark) dark:text-(--color-light)">
                @yield('content')
            </main>
        </div>

    </div>

    @routes

    {{-- Scripts --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/blockUi.js') }}"></script>
    <script src="{{ asset('js/dataTables.min.js') }}"></script>
    <script>
        window.DataTable = window.jQuery.fn.dataTable;
    </script>


    {{-- LANG --}}
    @include('layouts.lang')

    {{-- Js Vite --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
</body>

</html>
