<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    {{-- Init Theme --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css', 'resources/css/theme.css'])

    @stack('styles')
</head>

<body>
    <x-custom-alert top="top-5" right="right-4" align="align-end" />
    <div class="bg-(--color-light) text-(--color-dark) dark:bg-(--color-dark) dark:text-(--color-light)">
        @yield('content')
    </div>

    @routes

    {{-- Scripts --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>

    @stack('scripts')

    {{-- Js classVite --}}
    @vite(['resources/js/app.js'])
</body>

</html>
