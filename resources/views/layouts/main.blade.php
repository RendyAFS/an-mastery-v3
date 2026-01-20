<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'AN Mastery' }}</title>

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css', 'resources/css/theme.css'])
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
    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
        // lucide icons
        lucide.createIcons();
    </script>

    {{-- Js Vite --}}
    @vite(['resources/js/app.js'])
</body>

</html>
