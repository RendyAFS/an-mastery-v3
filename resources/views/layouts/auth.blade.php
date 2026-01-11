<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/css/theme.css', 'resources/js/app.js'])
</head>

<body x-data class="bg-(--color-light) text-(--color-dark) dark:bg-(--color-dark) dark:text-(--color-light)">
    <x-toast />

    @yield('content')

    <script src="{{ asset('js/luicide-latest.js') }}"></script>
</body>


</html>
