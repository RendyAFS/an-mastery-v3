<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        (function() {
            try {
                var mode = localStorage.getItem('sidebar:mode') || 'sidebar';
                var isDesktop = window.matchMedia('(min-width: 1024px)').matches;
                if (!isDesktop || mode === 'floating') {
                    document.documentElement.classList.add('sidebar-floating-init');
                }
            } catch (e) {}
        })();
    </script>
    @PwaHead
    <title>{{ $title ?? 'AN Mastery' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo-AnMastery.ico') }}">

    <script src="{{ asset('js/luicide-latest.js') }}" defer></script>

    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/dataTables.min.css') }}">

    @vite(['resources/css/app.css'])

    @stack('styles')
</head>

<body>
    <x-sidebar />

    <div id="app-content-area" class="transition-all duration-300 lg:ms-64 hs-overlay-minified:lg:ms-14">
        <div class="min-h-screen flex flex-col">
            <x-navbar />

            <main
                class="flex-1 px-2 md:px-10 py-8
                bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                text-(--color-dark) dark:text-(--color-light)">
                @yield('content')
            </main>
        </div>
    </div>

    @routes

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/blockUi.js') }}"></script>
    <script src="{{ asset('js/dataTables.min.js') }}"></script>
    <script>
        window.DataTable = window.jQuery.fn.dataTable;
    </script>

    @include('layouts.lang')

    @vite(['resources/js/app.js'])

    @stack('scripts')
    @RegisterServiceWorkerScript
</body>

</html>
