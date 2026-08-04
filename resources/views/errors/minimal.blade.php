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
        @php $code =trim((string) View::yieldContent('code'));
        $fallbackMessage =trim((string) View::yieldContent('message'));
        $entry =__('errors.codes.' . $code);
        $isKnown =is_array($entry);
        $title =$isKnown ? $entry['title'] : ($fallbackMessage ?: __('errors.default_title'));
        $tagline =$isKnown ? $entry['message'] : __('errors.default_message');
        @endphp

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
            border: 2px dashed color-mix(in srgb, var(--color-primary) 55%, transparent);
            border-radius: 20px;
            transform-origin: top center;
            box-shadow: 0 24px 48px -20px rgba(0, 0, 0, 0.25);
            animation: tag-hang 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .dark .error-tag {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-primary) 65%, transparent);
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
            border: 2px dashed color-mix(in srgb, var(--color-primary) 55%, transparent);
        }

        .dark .error-tag::before {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-primary) 65%, transparent);
        }

        .error-thread {
            position: absolute;
            top: -46px;
            left: 50%;
            transform: translateX(-50%);
            width: 32px;
            height: 40px;
        }

        .error-thread path {
            stroke-dasharray: 90;
            stroke-dashoffset: 90;
            animation: thread-draw 0.9s ease-out 0.15s both;
        }

        @keyframes thread-draw {
            to {
                stroke-dashoffset: 0;
            }
        }

        .error-eyebrow {
            opacity: 0;
            animation: fade-rise 0.5s ease-out 0.55s both;
        }

        .error-code {
            font-family: "Fraunces", ui-serif, Georgia, serif;
            font-weight: 600;
            font-size: clamp(3.5rem, 10vw, 6rem);
            line-height: 1;
            letter-spacing: -0.02em;
            color: var(--color-primary);
            opacity: 0;
            animation: fade-rise 0.55s ease-out 0.65s both;
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

        .error-stitch {
            border-top: 2px dashed color-mix(in srgb, var(--color-dark-gray) 50%, transparent);
            width: 100%;
            transform: scaleX(0);
            transform-origin: left center;
            animation: stitch-sew 0.6s ease-out 0.85s both;
        }

        @keyframes stitch-sew {
            to {
                transform: scaleX(1);
            }
        }

        .error-tagline {
            opacity: 0;
            animation: fade-rise 0.5s ease-out 1.05s both;
        }

        .error-btn-wrap {
            opacity: 0;
            animation: fade-rise 0.5s ease-out 1.2s both;
        }

        .error-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            border-radius: 999px;
            background-color: var(--color-primary);
            color: var(--color-light);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .error-btn:hover {
            opacity: 0.88;
            transform: translateY(-2px);
        }

        .error-btn:active {
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {

            .error-bg,
            .error-tag,
            .error-thread path,
            .error-eyebrow,
            .error-code,
            .error-stitch,
            .error-tagline,
            .error-btn-wrap {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>

    @stack('styles')
</head>

<body class="antialiased">
    <div class="error-bg min-h-screen flex items-center justify-center px-4 py-16">
        <div class="error-tag w-full max-w-md px-8 pt-14 pb-10 text-center">
            <svg class="error-thread" viewBox="0 0 32 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 0 C16 14, 4 12, 6 22 C8 32, 20 26, 16 40" stroke="var(--color-primary)" stroke-width="1.5"
                    stroke-dasharray="3 4" stroke-linecap="round" fill="none" />
            </svg>

            <p class="error-eyebrow text-xs font-semibold tracking-widest uppercase mb-2"
                style="color: color-mix(in srgb, var(--color-dark-gray) 90%, transparent);">
                {{ $title }}
            </p>

            <h1 class="error-code mb-6">@yield('code')</h1>

            <div class="error-stitch mb-6"></div>

            <p class="error-tagline text-sm leading-relaxed mb-8" style="color: var(--color-dark-gray);">
                {{ $tagline }}
            </p>

            <div class="error-btn-wrap">
                @auth
                    <a href="{{ route('dashboard') }}" class="error-btn">
                        <i data-lucide="home" class="size-4"></i>
                        {{ __('errors.back_to_home') }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="error-btn">
                        <i data-lucide="log-in" class="size-4"></i>
                        {{ __('errors.back_to_home') }}
                    </a>
                @endauth
            </div>
        </div>
    </div>

    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
        if (window.lucide) lucide.createIcons();
    </script>

    @stack('scripts')
</body>

</html>
