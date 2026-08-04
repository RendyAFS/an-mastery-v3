<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap"
        rel="stylesheet">

    {{-- Init Theme --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    @vite(['resources/css/app.css'])

    <style>
        html,
        body {
            height: 100%;
        }

        .error-bg {
            background-color: var(--color-light);
            background-image:
                repeating-linear-gradient(45deg, var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px),
                repeating-linear-gradient(-45deg, var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px);
            background-size: 20px 20px;
            animation: weave-drift 40s linear infinite;
        }

        .dark .error-bg {
            background-color: var(--color-dark);
            background-image:
                repeating-linear-gradient(45deg, var(--color-dark-slate) 0, var(--color-dark-slate) 1px, transparent 1px, transparent 14px),
                repeating-linear-gradient(-45deg, var(--color-dark-slate) 0, var(--color-dark-slate) 1px, transparent 1px, transparent 14px);
        }

        @keyframes weave-drift {
            from {
                background-position: 0 0, 0 0;
            }

            to {
                background-position: 400px 400px, -400px 400px;
            }
        }

        .error-tag {
            position: relative;
            background-color: var(--color-light);
            border: 2px dashed color-mix(in srgb, var(--color-danger) 55%, transparent);
            border-radius: 20px;
            transform-origin: top center;
            box-shadow: 0 24px 48px -20px rgba(0, 0, 0, 0.25);
            animation: tag-hang 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .dark .error-tag {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-danger) 65%, transparent);
        }

        @keyframes tag-hang {
            0% {
                opacity: 0;
                transform: translateY(-24px) rotate(0deg);
            }

            60% {
                opacity: 1;
                transform: translateY(2px) rotate(-2deg);
            }

            100% {
                opacity: 1;
                transform: translateY(0) rotate(-1.5deg);
            }
        }

        .error-tag::before {
            content: "";
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            width: 24px;
            height: 24px;
            border-radius: 999px;
            background-color: var(--color-light);
            border: 2px dashed color-mix(in srgb, var(--color-danger) 55%, transparent);
        }

        .dark .error-tag::before {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-danger) 65%, transparent);
        }

        .error-eyebrow {
            opacity: 0;
            animation: fade-rise 0.5s ease-out 0.5s both;
        }

        .error-title {
            font-family: "Fraunces", ui-serif, Georgia, serif;
            font-weight: 600;
            font-size: clamp(1.5rem, 5vw, 2.25rem);
            line-height: 1.2;
            color: var(--color-dark);
            opacity: 0;
            animation: fade-rise 0.55s ease-out 0.65s both;
        }

        .dark .error-title {
            color: var(--color-light);
        }

        @keyframes fade-rise {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .error-bg,
            .error-tag,
            .error-eyebrow,
            .error-title {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body class="antialiased">
    <div class="error-bg min-h-screen flex items-center justify-center px-4 py-16">
        <div class="error-tag w-full max-w-md px-8 pt-12 pb-10 text-center">
            <p class="error-eyebrow text-xs font-semibold tracking-widest uppercase mb-4"
                style="color: color-mix(in srgb, var(--color-danger) 85%, transparent);">
                {{ __('errors.default_title') }}
            </p>

            <div class="error-title">
                @yield('message')
            </div>
        </div>
    </div>
</body>

</html>
