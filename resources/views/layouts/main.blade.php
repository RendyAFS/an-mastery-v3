<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'AN Mastery' }}</title>

    {{-- Check Theme --}}
    <script>
        (() => {
            const html = document.documentElement;
            const stored = localStorage.getItem('hs_theme') || 'auto';
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (stored === 'dark' || (stored === 'auto' && prefersDark)) {
                html.classList.add('dark');
                html.classList.remove('light');
            } else {
                html.classList.add('light');
                html.classList.remove('dark');
            }
        })();
    </script>

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css', 'resources/css/theme.css'])

    @stack('styles')
</head>

<body class="bg-(--color-light) text-(--color-dark) dark:bg-(--color-dark) dark:text-(--color-light)">
    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Main Content Area --}}
    <div class="transition-all duration-300 lg:ms-64 hs-overlay-minified:lg:ms-14">
        {{-- Navbar --}}
        @include('components.navbar')

        {{-- Content --}}
        <main class="p-4 md:p-6 lg:p-8 min-h-screen bg-(--color-light-gray) dark:bg-(--color-dark)">
            @yield('content')
        </main>
    </div>

    @routes

    {{-- Scripts --}}
    {{-- jquery --}}
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    {{-- block ui --}}
    <script src="{{ asset('js/blockUi.js') }}"></script>
    {{-- lucide --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
        lucide.createIcons();
    </script>

    {{-- Js Vite --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
</body>

</html>
