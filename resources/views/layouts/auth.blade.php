<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo-AnMastery.ico') }}">

    {{-- PWA Head Meta & Links --}}
    <meta name="theme-color" content="#6d9886">
    @if (app()->environment('production'))
        <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    @endif
    <link rel="apple-touch-icon" href="{{ asset('assets/pwa-192x192.png') }}">

    {{-- Scripts --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>

    {{-- Init Theme --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    {{-- Styles Vite --}}
    @vite(['resources/js/app.js', 'resources/css/app.css'])

    @stack('styles')
</head>

<body>
    <x-custom-alert top="top-5" right="right-4" align="align-end" />
    <div class="bg-(--color-light) text-(--color-dark) dark:bg-(--color-dark) dark:text-(--color-light)">
        @yield('content')
    </div>

    @routes

    @stack('scripts')
</body>

</html>
