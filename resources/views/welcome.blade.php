<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ __('welcome.meta_desc') }}">
    <title>{{ __('welcome.title') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Init Theme (sebelum CSS) --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Typography ──────────────────────────────────────────────── */
        :root {
            --font-display: 'Fraunces', ui-serif, Georgia, serif;
            --font-body: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
        body { font-family: var(--font-body); }

        /* ── Animated Weave Background (konsisten dengan error pages) ── */
        .weave-bg {
            background-color: var(--color-light);
            background-image:
                repeating-linear-gradient(45deg,  var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px),
                repeating-linear-gradient(-45deg, var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px);
            background-size: 20px 20px;
            animation: weave-drift 60s linear infinite;
        }
        .dark .weave-bg {
            background-color: var(--color-dark);
            background-image:
                repeating-linear-gradient(45deg,  var(--color-dark-slate) 0, var(--color-dark-slate) 1px, transparent 1px, transparent 14px),
                repeating-linear-gradient(-45deg, var(--color-dark-slate) 0, var(--color-dark-slate) 1px, transparent 1px, transparent 14px);
        }
        @keyframes weave-drift {
            from { background-position: 0 0, 0 0; }
            to   { background-position: 400px 400px, -400px 400px; }
        }

        /* ── Display Font ─────────────────────────────────────────────── */
        .font-display { font-family: var(--font-display); }

        /* ── Hero Gradient Headline ───────────────────────────────────── */
        .hero-em {
            font-family: var(--font-display);
            font-style: italic;
            background: linear-gradient(135deg, var(--color-primary) 0%, color-mix(in srgb, var(--color-primary) 60%, var(--color-secondary)) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ── Badge pill ───────────────────────────────────────────────── */
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.85rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            background-color: color-mix(in srgb, var(--color-primary) 12%, transparent);
            color: var(--color-primary);
            border: 1px solid color-mix(in srgb, var(--color-primary) 30%, transparent);
        }
        .dark .badge-pill {
            background-color: color-mix(in srgb, var(--color-primary) 18%, transparent);
            border-color: color-mix(in srgb, var(--color-primary) 40%, transparent);
        }

        /* ── CTA Buttons ──────────────────────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 999px;
            background-color: var(--color-primary);
            color: var(--color-light);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: opacity 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 16px -4px color-mix(in srgb, var(--color-primary) 55%, transparent);
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -6px color-mix(in srgb, var(--color-primary) 65%, transparent);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 999px;
            border: 1.5px solid color-mix(in srgb, var(--color-primary) 45%, transparent);
            color: var(--color-primary);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .btn-ghost:hover {
            background-color: color-mix(in srgb, var(--color-primary) 10%, transparent);
            transform: translateY(-2px);
        }
        .btn-ghost:active { transform: translateY(0); }

        /* ── Feature Card ─────────────────────────────────────────────── */
        .feature-card {
            position: relative;
            background-color: var(--color-light);
            border: 1px solid color-mix(in srgb, var(--color-gray) 35%, transparent);
            border-radius: 1.25rem;
            padding: 1.75rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            overflow: hidden;
        }
        .feature-card::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: linear-gradient(135deg, color-mix(in srgb, var(--color-primary) 6%, transparent), transparent 60%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -8px rgba(0,0,0,0.12);
            border-color: color-mix(in srgb, var(--color-primary) 40%, transparent);
        }
        .dark .feature-card {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-gray) 20%, transparent);
        }
        .dark .feature-card:hover {
            border-color: color-mix(in srgb, var(--color-primary) 50%, transparent);
            box-shadow: 0 12px 32px -8px rgba(0,0,0,0.3);
        }

        .feature-icon-wrap {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            background-color: color-mix(in srgb, var(--color-primary) 14%, transparent);
            color: var(--color-primary);
            margin-bottom: 1rem;
            flex-shrink: 0;
        }
        .dark .feature-icon-wrap {
            background-color: color-mix(in srgb, var(--color-primary) 20%, transparent);
        }

        /* ── Stat Card ────────────────────────────────────────────────── */
        .stat-card {
            text-align: center;
            padding: 1.5rem 1rem;
            border-radius: 1.25rem;
            background-color: color-mix(in srgb, var(--color-primary) 8%, var(--color-light));
            border: 1px solid color-mix(in srgb, var(--color-primary) 18%, transparent);
        }
        .dark .stat-card {
            background-color: color-mix(in srgb, var(--color-primary) 10%, var(--color-dark-slate));
            border-color: color-mix(in srgb, var(--color-primary) 25%, transparent);
        }
        .stat-num {
            font-family: var(--font-display);
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 600;
            line-height: 1;
            color: var(--color-primary);
        }

        /* ── Stitch Divider ───────────────────────────────────────────── */
        .stitch-line {
            border: none;
            border-top: 2px dashed color-mix(in srgb, var(--color-gray) 40%, transparent);
        }
        .dark .stitch-line {
            border-top-color: color-mix(in srgb, var(--color-dark-gray) 30%, transparent);
        }

        /* ── Navbar ───────────────────────────────────────────────────── */
        .landing-nav {
            background-color: color-mix(in srgb, var(--color-light) 85%, transparent);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid color-mix(in srgb, var(--color-gray) 25%, transparent);
        }
        .dark .landing-nav {
            background-color: color-mix(in srgb, var(--color-dark) 85%, transparent);
            border-bottom-color: color-mix(in srgb, var(--color-dark-gray) 20%, transparent);
        }

        /* ── Scroll Animations ────────────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        .reveal-delay-5 { transition-delay: 0.5s; }

        /* ── Thread SVG Decoration ────────────────────────────────────── */
        .thread-svg path {
            stroke-dasharray: 200;
            stroke-dashoffset: 200;
            animation: thread-draw 1.8s ease-out 0.3s both;
        }
        @keyframes thread-draw { to { stroke-dashoffset: 0; } }

        /* Hero entrance */
        .hero-enter { animation: hero-rise 0.8s cubic-bezier(0.22, 1, 0.36, 1) both; }
        .hero-enter-delay-1 { animation-delay: 0.1s; }
        .hero-enter-delay-2 { animation-delay: 0.25s; }
        .hero-enter-delay-3 { animation-delay: 0.45s; }
        @keyframes hero-rise {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .weave-bg, .thread-svg path, .hero-enter, .reveal {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
                stroke-dashoffset: 0 !important;
            }
        }
    </style>
</head>

<body class="text-(--color-dark) dark:text-(--color-light) antialiased">

    {{-- ═══════════════════════════════════════════════════════════
         NAVBAR
    ═══════════════════════════════════════════════════════════ --}}
    <nav class="landing-nav sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('landing_page') }}" class="flex items-center gap-2.5 no-underline">
                {{-- Needle + thread icon (inline SVG, brand motif) --}}
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <rect width="28" height="28" rx="8" fill="var(--color-primary)" opacity="0.12"/>
                    {{-- Needle body --}}
                    <path d="M9 19 L19 9" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round"/>
                    {{-- Eye of needle --}}
                    <circle cx="19.5" cy="8.5" r="2" stroke="var(--color-primary)" stroke-width="1.5" fill="none"/>
                    {{-- Thread wave --}}
                    <path d="M9 19 C7 21, 5 21, 6 23 C7 25, 10 24, 9 26" stroke="var(--color-primary)" stroke-width="1.5" stroke-linecap="round" stroke-dasharray="2 2" fill="none"/>
                </svg>
                <span class="font-display font-semibold text-base tracking-tight text-(--color-dark) dark:text-(--color-light)">AN Mastery</span>
            </a>

            {{-- Right controls --}}
            <div class="flex items-center gap-1">
                {{-- Language toggle --}}
                @include('components.toggle-language')

                {{-- Theme toggle --}}
                @include('components.toggle-theme')

                {{-- Auth CTA --}}
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="ms-1 inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold
                        bg-(--color-primary) text-(--color-light)
                        hover:opacity-90 transition-opacity">
                        <i data-lucide="layout-dashboard" class="size-3.5"></i>
                        {{ __('welcome.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="ms-1 hidden sm:inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-medium
                        text-(--color-dark) dark:text-(--color-light)
                        hover:bg-(--color-gray)/20 transition-colors">
                        {{ __('welcome.nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-sm font-semibold
                        bg-(--color-primary) text-(--color-light)
                        hover:opacity-90 transition-opacity">
                        {{ __('welcome.nav.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════════ --}}
    <div class="weave-bg min-h-screen flex flex-col">

        {{-- ── HERO SECTION ─────────────────────────────────────────── --}}
        <section class="flex-1 flex items-center justify-center px-4 sm:px-6 py-24 sm:py-32 relative overflow-hidden">

            {{-- Decorative background thread —large, subtle --}}
            <svg class="thread-svg absolute right-0 top-0 -translate-y-1/4 translate-x-1/4 opacity-10 dark:opacity-[0.07] pointer-events-none"
                width="480" height="480" viewBox="0 0 480 480" fill="none" aria-hidden="true">
                <path d="M240 0 C240 160, 60 140, 80 260 C100 380, 320 320, 260 440 C200 560, 20 520, 60 440"
                    stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6 8" stroke-linecap="round" fill="none"/>
                <path d="M400 40 C380 120, 300 100, 320 180 C340 260, 440 240, 420 320"
                    stroke="var(--color-secondary)" stroke-width="1.5" stroke-dasharray="4 6" stroke-linecap="round" fill="none"/>
            </svg>

            <svg class="thread-svg absolute left-0 bottom-0 translate-y-1/4 -translate-x-1/4 opacity-10 dark:opacity-[0.07] pointer-events-none"
                width="360" height="360" viewBox="0 0 360 360" fill="none" aria-hidden="true">
                <path d="M60 360 C80 260, 180 280, 160 180 C140 80, 20 100, 40 20"
                    stroke="var(--color-primary)" stroke-width="2" stroke-dasharray="6 8" stroke-linecap="round" fill="none"/>
            </svg>

            <div class="max-w-3xl w-full text-center relative z-10">
                {{-- Location badge --}}
                <div class="hero-enter inline-block">
                    <span class="badge-pill">
                        <i data-lucide="map-pin" class="size-3"></i>
                        {{ __('welcome.hero.badge') }}
                    </span>
                </div>

                {{-- Headline --}}
                <h1 class="hero-enter hero-enter-delay-1 mt-6 font-display font-semibold tracking-tight
                    text-(--color-dark) dark:text-(--color-light)"
                    style="font-size: clamp(2.75rem, 8vw, 5rem); line-height: 1.1;">
                    {{ __('welcome.hero.headline') }}
                    <br>
                    <span class="hero-em">{{ __('welcome.hero.headline_em') }}</span>
                </h1>

                {{-- Sub-headline --}}
                <p class="hero-enter hero-enter-delay-2 mt-6 text-base sm:text-lg leading-relaxed max-w-xl mx-auto"
                    style="color: var(--color-dark-gray);">
                    {{ __('welcome.hero.subheadline') }}
                </p>

                {{-- CTA buttons --}}
                <div class="hero-enter hero-enter-delay-3 mt-10 flex flex-col sm:flex-row gap-3 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-primary" id="hero-cta-dashboard">
                            <i data-lucide="layout-dashboard" class="size-4"></i>
                            {{ __('welcome.nav.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary" id="hero-cta-login">
                            <i data-lucide="log-in" class="size-4"></i>
                            {{ __('welcome.hero.cta_login') }}
                        </a>
                        <a href="{{ route('register') }}" class="btn-ghost" id="hero-cta-register">
                            <i data-lucide="user-plus" class="size-4"></i>
                            {{ __('welcome.hero.cta_register') }}
                        </a>
                    @endauth
                </div>

                {{-- Scroll indicator --}}
                <div class="hero-enter hero-enter-delay-3 mt-16 flex justify-center opacity-40">
                    <div class="flex flex-col items-center gap-1 animate-bounce">
                        <span class="text-xs font-medium tracking-widest uppercase" style="color: var(--color-dark-gray);">scroll</span>
                        <i data-lucide="chevrons-down" class="size-4" style="color: var(--color-dark-gray);"></i>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── STATS STRIP ──────────────────────────────────────────── --}}
        <section class="py-12 px-4 sm:px-6" aria-labelledby="stats-title">
            <div class="max-w-4xl mx-auto">
                <hr class="stitch-line mb-10">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 reveal" data-reveal>
                    <div class="stat-card" id="stat-suppliers">
                        <p class="stat-num">10+</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">{{ __('welcome.stats.suppliers') }}</p>
                    </div>
                    <div class="stat-card" id="stat-employees">
                        <p class="stat-num">30+</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">{{ __('welcome.stats.employees') }}</p>
                    </div>
                    <div class="stat-card" id="stat-modules">
                        <p class="stat-num">12</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">{{ __('welcome.stats.modules') }}</p>
                    </div>
                    <div class="stat-card" id="stat-languages">
                        <p class="stat-num">2</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">{{ __('welcome.stats.languages') }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── FEATURES SECTION ─────────────────────────────────────── --}}
        <section class="py-16 sm:py-20 px-4 sm:px-6" aria-labelledby="features-title">
            <div class="max-w-6xl mx-auto">

                {{-- Section header --}}
                <div class="text-center mb-12 reveal" data-reveal>
                    <p class="text-xs font-semibold tracking-widest uppercase mb-3" style="color: var(--color-primary);">
                        Features
                    </p>
                    <h2 id="features-title" class="font-display font-semibold text-(--color-dark) dark:text-(--color-light)"
                        style="font-size: clamp(1.75rem, 4vw, 2.5rem);">
                        {{ __('welcome.features.title') }}
                    </h2>
                    <p class="mt-3 text-base leading-relaxed max-w-xl mx-auto" style="color: var(--color-dark-gray);">
                        {{ __('welcome.features.subtitle') }}
                    </p>
                </div>

                {{-- Feature cards grid --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">

                    {{-- Fabric Inventory --}}
                    <div class="feature-card reveal reveal-delay-1" data-reveal id="feature-fabric">
                        <div class="feature-icon-wrap">
                            <i data-lucide="layers" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.fabric.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.fabric.desc') }}
                        </p>
                        {{-- Stitch accent --}}
                        <div class="mt-4 border-t-2 border-dashed" style="border-color: color-mix(in srgb, var(--color-primary) 20%, transparent);"></div>
                    </div>

                    {{-- Sablon Orders --}}
                    <div class="feature-card reveal reveal-delay-2" data-reveal id="feature-sablon">
                        <div class="feature-icon-wrap">
                            <i data-lucide="printer" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.sablon.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.sablon.desc') }}
                        </p>
                        <div class="mt-4 border-t-2 border-dashed" style="border-color: color-mix(in srgb, var(--color-primary) 20%, transparent);"></div>
                    </div>

                    {{-- Supplier Billing --}}
                    <div class="feature-card reveal reveal-delay-3" data-reveal id="feature-billing">
                        <div class="feature-icon-wrap">
                            <i data-lucide="receipt" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.billing.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.billing.desc') }}
                        </p>
                        <div class="mt-4 border-t-2 border-dashed" style="border-color: color-mix(in srgb, var(--color-primary) 20%, transparent);"></div>
                    </div>

                    {{-- Employee & Salary --}}
                    <div class="feature-card reveal reveal-delay-1" data-reveal id="feature-employee">
                        <div class="feature-icon-wrap">
                            <i data-lucide="users" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.employee.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.employee.desc') }}
                        </p>
                        <div class="mt-4 border-t-2 border-dashed" style="border-color: color-mix(in srgb, var(--color-primary) 20%, transparent);"></div>
                    </div>

                    {{-- Attendance --}}
                    <div class="feature-card reveal reveal-delay-2" data-reveal id="feature-presence">
                        <div class="feature-icon-wrap">
                            <i data-lucide="calendar-check" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.presence.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.presence.desc') }}
                        </p>
                        <div class="mt-4 border-t-2 border-dashed" style="border-color: color-mix(in srgb, var(--color-primary) 20%, transparent);"></div>
                    </div>

                    {{-- CTA card --}}
                    <div class="feature-card reveal reveal-delay-3" data-reveal id="feature-cta"
                        style="background: linear-gradient(135deg, color-mix(in srgb, var(--color-primary) 15%, var(--color-light)), color-mix(in srgb, var(--color-secondary) 30%, var(--color-light)));
                               border-color: color-mix(in srgb, var(--color-primary) 30%, transparent);">
                        <div class="h-full flex flex-col justify-between">
                            <div>
                                {{-- Needle decorative SVG --}}
                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" aria-hidden="true" class="mb-4 opacity-70">
                                    <path d="M8 28 L28 8" stroke="var(--color-primary)" stroke-width="2.5" stroke-linecap="round"/>
                                    <circle cx="29" cy="7" r="3" stroke="var(--color-primary)" stroke-width="2" fill="none"/>
                                    <path d="M8 28 C5 31, 3 31, 4 33 C5 35, 9 34, 7 36" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-dasharray="2 2.5" fill="none"/>
                                </svg>
                                <h3 class="font-display font-semibold text-lg mb-2 text-(--color-dark) dark:text-(--color-light)">
                                    Ready to start?
                                </h3>
                                <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                                    Access all features and manage your convection business from one dashboard.
                                </p>
                            </div>
                            <div class="mt-6">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn-primary text-sm" style="padding: 0.6rem 1.25rem;">
                                        <i data-lucide="layout-dashboard" class="size-4"></i>
                                        {{ __('welcome.nav.dashboard') }}
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-primary text-sm" style="padding: 0.6rem 1.25rem;">
                                        <i data-lucide="log-in" class="size-4"></i>
                                        {{ __('welcome.hero.cta_login') }}
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>

                </div>{{-- /grid --}}
            </div>
        </section>

        {{-- ── FOOTER ───────────────────────────────────────────────── --}}
        <footer class="py-8 px-4 sm:px-6">
            <div class="max-w-6xl mx-auto">
                <hr class="stitch-line mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm" style="color: var(--color-dark-gray);">
                    <div class="flex items-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 28 28" fill="none" aria-hidden="true">
                            <path d="M9 19 L19 9" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round"/>
                            <circle cx="19.5" cy="8.5" r="2" stroke="var(--color-primary)" stroke-width="1.5" fill="none"/>
                        </svg>
                        <span class="font-semibold text-(--color-dark) dark:text-(--color-light)">AN Mastery</span>
                        <span>·</span>
                        <span>{{ __('welcome.footer.tagline') }}</span>
                    </div>
                    <p>{{ str_replace(':year', date('Y'), __('welcome.footer.copyright')) }}</p>
                </div>
            </div>
        </footer>

    </div>{{-- /weave-bg --}}

    {{-- Scripts --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
        // Init Lucide icons
        if (window.lucide) lucide.createIcons();

        // Scroll reveal
        const reveals = document.querySelectorAll('[data-reveal]');
        const io = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => io.observe(el));
    </script>
</body>

</html>
