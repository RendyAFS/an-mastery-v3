<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

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

<body>
    <x-custom-toast top="top-5" right="right-4" align="align-end"/>
    <div class="bg-(--color-light) text-(--color-dark) dark:bg-(--color-dark) dark:text-(--color-light)">
        @yield('content')
    </div>

    @routes

    {{-- Scripts --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
        // lucide icons
        lucide.createIcons();
    </script>

    {{-- Js classVite --}}
    @vite(['resources/js/app.js'])

    @stack('scripts')
</body>

</html>
