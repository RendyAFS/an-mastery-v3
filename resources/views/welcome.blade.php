<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="{{ __('welcome.meta_desc') }}">
    @PwaHead
    <title>{{ __('welcome.title') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/Logo-AnMastery.ico') }}">

    {{-- Init Theme (sebelum CSS) --}}
    <script src="{{ Vite::asset('resources/js/utils/init-theme.js') }}"></script>

    {{-- Styles Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @font-face {
            font-family: 'Fraunces';
            font-style: normal;
            font-weight: 400 700;
            font-display: swap;
            src: url('{{ asset('fonts/fraunces/Fraunces-Variable.woff2') }}') format('woff2');
        }

        @font-face {
            font-family: 'Fraunces';
            font-style: italic;
            font-weight: 400 700;
            font-display: swap;
            src: url('{{ asset('fonts/fraunces/Fraunces-Italic-Variable.woff2') }}') format('woff2');
        }

        @font-face {
            font-family: 'Inter';
            font-style: normal;
            font-weight: 300 600;
            font-display: swap;
            src: url('{{ asset('fonts/inter/Inter-Variable.woff2') }}') format('woff2');
        }

        /* ── Typography ──────────────────────────────────────────────── */
        :root {
            --font-display: 'Fraunces', ui-serif, Georgia, serif;
            --font-body: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }

        html,
        body {
            max-width: 100vw;
            overflow-x: hidden;
        }

        * {
            min-width: 0;
        }

        .badge-pill {
            white-space: normal;
            text-align: center;
        }

        body {
            font-family: var(--font-body);
        }

        /* ── Animated Weave Background ───────────────────────────────── */
        .weave-bg {
            background-color: var(--color-light);
            background-image:
                repeating-linear-gradient(45deg, var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px),
                repeating-linear-gradient(-45deg, var(--color-light-gray) 0, var(--color-light-gray) 1px, transparent 1px, transparent 14px);
            background-size: 20px 20px;
            animation: weave-drift 60s linear infinite;
        }

        .dark .weave-bg {
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

        /* ── Display Font ─────────────────────────────────────────────── */
        .font-display {
            font-family: var(--font-display);
        }

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

        /* ── Buttons ─────────────────────────────────────────────────── */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .btn-ghost:active {
            transform: translateY(0);
        }

        /* ── Showcase Card ────────────────────────────────────────────── */
        .showcase-card {
            position: relative;
            background-color: var(--color-light);
            border: 1px solid color-mix(in srgb, var(--color-gray) 35%, transparent);
            border-radius: 1.25rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .showcase-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.15);
            border-color: color-mix(in srgb, var(--color-primary) 50%, transparent);
        }

        .dark .showcase-card {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-gray) 20%, transparent);
        }

        .dark .showcase-card:hover {
            border-color: color-mix(in srgb, var(--color-primary) 60%, transparent);
            box-shadow: 0 16px 36px -10px rgba(0, 0, 0, 0.4);
        }

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

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px -8px rgba(0, 0, 0, 0.12);
            border-color: color-mix(in srgb, var(--color-primary) 40%, transparent);
        }

        .dark .feature-card {
            background-color: var(--color-dark-slate);
            border-color: color-mix(in srgb, var(--color-gray) 20%, transparent);
        }

        .feature-icon-wrap {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: color-mix(in srgb, var(--color-primary) 14%, transparent);
            color: var(--color-primary);
            margin-bottom: 1rem;
            flex-shrink: 0;
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

        /* ── Reveal ───────────────────────────────────────────────────── */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        /* Hero entrance */
        .hero-enter {
            animation: hero-rise 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        .hero-enter-delay-1 {
            animation-delay: 0.1s;
        }

        .hero-enter-delay-2 {
            animation-delay: 0.25s;
        }

        .hero-enter-delay-3 {
            animation-delay: 0.45s;
        }

        @keyframes hero-rise {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        [x-cloak] {
            display: none !important;
        }

        @media (prefers-reduced-motion: reduce) {

            .weave-bg,
            .hero-enter,
            .reveal {
                animation: none !important;
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body class="text-(--color-dark) dark:text-(--color-light) antialiased" x-data="{
    modalOpen: false,
    activeImages: [],
    activeIndex: 0,
    activeTitle: '',
    activeSubtitle: '',
    activeBadge: '',
    openLightbox(images, title, subtitle, badge) {
        this.activeImages = images;
        this.activeIndex = 0;
        this.activeTitle = title;
        this.activeSubtitle = subtitle;
        this.activeBadge = badge;
        this.modalOpen = true;
        this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
    },
    prevImage() {
        this.activeIndex = (this.activeIndex - 1 + this.activeImages.length) % this.activeImages.length;
    },
    nextImage() {
        this.activeIndex = (this.activeIndex + 1) % this.activeImages.length;
    }
}"
    x-effect="document.body.style.overflow = modalOpen ? 'hidden' : ''">

    {{-- NAVBAR --}}
    <nav class="landing-nav sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('landing_page') }}" class="flex items-center gap-2.5 no-underline">
                <img src="{{ asset('assets/Logo-AnMastery.webp') }}" alt="AN Mastery Logo"
                    class="h-9 w-auto object-contain">
            </a>

            {{-- Controls --}}
            <div class="flex items-center gap-2">
                @include('components.toggle-language')
                @include('components.toggle-theme')

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="ms-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold
                        bg-(--color-primary) text-(--color-light)
                        hover:opacity-90 transition-opacity">
                        <i data-lucide="layout-dashboard" class="size-4"></i>
                        {{ __('welcome.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="ms-2 inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium
                        text-(--color-dark) dark:text-(--color-light)
                        hover:bg-(--color-gray)/20 transition-colors">
                        {{ __('welcome.nav.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold
                        bg-(--color-primary) text-(--color-light)
                        hover:opacity-90 transition-opacity">
                        {{ __('welcome.nav.register') }}
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MAIN CONTAINER --}}
    <div class="weave-bg min-h-screen flex flex-col">

        {{-- HERO SECTION --}}
        <section class="flex-1 flex items-center justify-center px-4 sm:px-6 py-20 sm:py-28 relative overflow-hidden">
            <div class="max-w-4xl w-full text-center relative z-10">

                {{-- Location badge --}}
                <div class="hero-enter inline-block mb-4">
                    <span class="badge-pill">
                        <i data-lucide="map-pin" class="size-3.5"></i>
                        {{ __('welcome.hero.badge') }}
                    </span>
                </div>

                {{-- Headline --}}
                <h1 class="hero-enter hero-enter-delay-1 font-display font-semibold tracking-tight
                    text-(--color-dark) dark:text-(--color-light)"
                    style="font-size: clamp(2.5rem, 7vw, 4.5rem); line-height: 1.1;">
                    {{ __('welcome.hero.headline') }}
                    <br>
                    <span class="hero-em">{{ __('welcome.hero.headline_em') }}</span>
                </h1>

                {{-- Subheadline --}}
                <p class="hero-enter hero-enter-delay-2 mt-6 text-base sm:text-lg leading-relaxed max-w-2xl mx-auto"
                    style="color: var(--color-dark-gray);">
                    {{ __('welcome.hero.subheadline') }}
                </p>

                {{-- CTA buttons --}}
                <div class="hero-enter hero-enter-delay-3 mt-8 flex flex-wrap gap-3 justify-center">
                    <a href="#showcase-gallery" class="btn-primary">
                        <i data-lucide="image" class="size-4.5"></i>
                        {{ __('welcome.hero.explore') }}
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn-ghost">
                            <i data-lucide="layout-dashboard" class="size-4.5"></i>
                            {{ __('welcome.nav.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-ghost">
                            <i data-lucide="log-in" class="size-4.5"></i>
                            {{ __('welcome.hero.cta_login') }}
                        </a>
                    @endauth
                </div>

            </div>
        </section>

        {{-- SHOWCASE SECTION 1: GALERI PRODUKSI --}}
        <section id="showcase-gallery" class="py-16 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 reveal" data-reveal>
                    <div>
                        <div class="badge-pill mb-2">
                            <i data-lucide="camera" class="size-3.5"></i>
                            {{ __('welcome.gallery.badge') }}
                        </div>
                        <h2
                            class="font-display font-semibold text-2xl sm:text-3xl text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.gallery.title') }}
                        </h2>
                        <p class="mt-2 text-sm sm:text-base max-w-2xl" style="color: var(--color-dark-gray);">
                            {{ __('welcome.gallery.subtitle') }}
                        </p>
                    </div>
                </div>

                @php
                    $galleriesWithMedia = $galleries->filter(
                        fn($g) => $g->getMedia('galleries')->isNotEmpty() || $g->getMedia()->isNotEmpty(),
                    );
                @endphp

                @if ($galleriesWithMedia->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($galleriesWithMedia as $item)
                            @php
                                $media = $item->getMedia('galleries')->isNotEmpty()
                                    ? $item->getMedia('galleries')
                                    : $item->getMedia();
                                $images = $media->pluck('original_url')->values();
                                $imgUrl = $images->first();
                                $imageCount = $images->count();
                            @endphp
                            <div class="showcase-card group cursor-pointer reveal" data-reveal
                                @click="openLightbox({{ Js::from($images) }}, '{{ e($item->name) }}', '{{ e($item->notes ?: '-') }}', 'Galeri Produksi')">
                                <div class="aspect-4/3 overflow-hidden bg-gray-100 dark:bg-slate-800 relative">
                                    <img src="{{ $imgUrl }}" alt="{{ $item->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy" />
                                    @if ($imageCount > 1)
                                        <span
                                            class="absolute bottom-2.5 right-2.5 px-2 py-1 rounded-full bg-black/60 text-white text-[11px] font-semibold flex items-center gap-1">
                                            <i data-lucide="images" class="size-3"></i> {{ $imageCount }}
                                        </span>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span
                                            class="px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-semibold border border-white/30 flex items-center gap-1.5">
                                            <i data-lucide="maximize-2" class="size-3.5"></i>
                                            {{ __('welcome.gallery.view_image') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3
                                        class="font-semibold text-base text-(--color-dark) dark:text-(--color-light) group-hover:text-(--color-primary) transition-colors line-clamp-1">
                                        {{ $item->name }}
                                    </h3>
                                    @if ($item->notes)
                                        <p class="mt-1 text-xs line-clamp-2" style="color: var(--color-dark-gray);">
                                            {{ $item->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State Fallback --}}
                    <div class="showcase-card p-12 text-center max-w-lg mx-auto reveal" data-reveal>
                        <div class="feature-icon-wrap mx-auto mb-4">
                            <i data-lucide="image-off" class="size-6"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.gallery.empty') }}
                        </h3>
                        <p class="mt-2 text-sm" style="color: var(--color-dark-gray);">
                            Foto galeri produksi Andri Sablon akan segera ditampilkan di sini.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        {{-- SHOWCASE SECTION 2: KATALOG KAIN --}}
        <section class="py-16 px-4 sm:px-6 bg-black/5 dark:bg-white/5">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 reveal" data-reveal>
                    <div>
                        <div class="badge-pill mb-2">
                            <i data-lucide="layers" class="size-3.5"></i>
                            {{ __('welcome.fabrics.badge') }}
                        </div>
                        <h2
                            class="font-display font-semibold text-2xl sm:text-3xl text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.fabrics.title') }}
                        </h2>
                        <p class="mt-2 text-sm sm:text-base max-w-2xl" style="color: var(--color-dark-gray);">
                            {{ __('welcome.fabrics.subtitle') }}
                        </p>
                    </div>
                </div>

                @php
                    $fabricsWithMedia = $imageFabrics->filter(
                        fn($f) => $f->getMedia('image-fabrics')->isNotEmpty() || $f->getMedia()->isNotEmpty(),
                    );
                @endphp

                @if ($fabricsWithMedia->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($fabricsWithMedia as $fabric)
                            @php
                                $media = $fabric->getMedia('image-fabrics')->isNotEmpty()
                                    ? $fabric->getMedia('image-fabrics')
                                    : $fabric->getMedia();
                                $images = $media->pluck('original_url')->values();
                                $imgUrl = $images->first();
                                $imageCount = $images->count();
                            @endphp
                            <div class="showcase-card group cursor-pointer reveal" data-reveal
                                @click="openLightbox({{ Js::from($images) }}, '{{ e($fabric->name) }}', '{{ e($fabric->notes ?: '-') }}', 'Katalog Kain')">
                                <div class="aspect-4/3 overflow-hidden bg-gray-100 dark:bg-slate-800 relative">
                                    <img src="{{ $imgUrl }}" alt="{{ $fabric->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        loading="lazy" />
                                    @if ($imageCount > 1)
                                        <span
                                            class="absolute bottom-2.5 right-2.5 px-2 py-1 rounded-full bg-black/60 text-white text-[11px] font-semibold flex items-center gap-1">
                                            <i data-lucide="images" class="size-3"></i> {{ $imageCount }}
                                        </span>
                                    @endif
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span
                                            class="px-3.5 py-1.5 rounded-full bg-white/20 backdrop-blur-md text-white text-xs font-semibold border border-white/30 flex items-center gap-1.5">
                                            <i data-lucide="maximize-2" class="size-3.5"></i>
                                            {{ __('welcome.gallery.view_image') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3
                                        class="font-semibold text-base text-(--color-dark) dark:text-(--color-light) group-hover:text-(--color-primary) transition-colors line-clamp-1">
                                        {{ $fabric->name }}
                                    </h3>
                                    @if ($fabric->notes)
                                        <p class="mt-1 text-xs line-clamp-2" style="color: var(--color-dark-gray);">
                                            {{ $fabric->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Empty State Fallback --}}
                    <div class="showcase-card p-12 text-center max-w-lg mx-auto reveal" data-reveal>
                        <div class="feature-icon-wrap mx-auto mb-4">
                            <i data-lucide="layers" class="size-6"></i>
                        </div>
                        <h3 class="font-semibold text-lg text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.fabrics.empty') }}
                        </h3>
                        <p class="mt-2 text-sm" style="color: var(--color-dark-gray);">
                            Katalog jenis dan warna kain akan ditampilkan di sini.
                        </p>
                    </div>
                @endif
            </div>
        </section>

        {{-- STATS STRIP --}}
        <section class="py-12 px-4 sm:px-6">
            <div class="max-w-4xl mx-auto">
                <hr class="stitch-line mb-10">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 reveal" data-reveal>
                    <div class="stat-card">
                        <p class="stat-num">10+</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">
                            {{ __('welcome.stats.suppliers') }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-num">30+</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">
                            {{ __('welcome.stats.employees') }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-num">12</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">
                            {{ __('welcome.stats.modules') }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-num">2</p>
                        <p class="mt-1 text-sm font-medium" style="color: var(--color-dark-gray);">
                            {{ __('welcome.stats.languages') }}</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- FEATURES OVERVIEW --}}
        <section class="py-16 sm:py-20 px-4 sm:px-6">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-12 reveal" data-reveal>
                    <p class="text-xs font-semibold tracking-widest uppercase mb-3"
                        style="color: var(--color-primary);">
                        Features
                    </p>
                    <h2 class="font-display font-semibold text-(--color-dark) dark:text-(--color-light)"
                        style="font-size: clamp(1.75rem, 4vw, 2.5rem);">
                        {{ __('welcome.features.title') }}
                    </h2>
                    <p class="mt-3 text-base leading-relaxed max-w-xl mx-auto" style="color: var(--color-dark-gray);">
                        {{ __('welcome.features.subtitle') }}
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="feature-card reveal reveal-delay-1" data-reveal>
                        <div class="feature-icon-wrap">
                            <i data-lucide="layers" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.fabric.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.fabric.desc') }}
                        </p>
                    </div>

                    <div class="feature-card reveal reveal-delay-2" data-reveal>
                        <div class="feature-icon-wrap">
                            <i data-lucide="printer" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.sablon.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.sablon.desc') }}
                        </p>
                    </div>

                    <div class="feature-card reveal reveal-delay-3" data-reveal>
                        <div class="feature-icon-wrap">
                            <i data-lucide="receipt" class="size-5"></i>
                        </div>
                        <h3 class="font-semibold text-base mb-2 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('welcome.features.billing.title') }}
                        </h3>
                        <p class="text-sm leading-relaxed" style="color: var(--color-dark-gray);">
                            {{ __('welcome.features.billing.desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- LOCATION SECTION --}}
        <section class="py-16 px-4 sm:px-6">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-10 reveal" data-reveal>
                    <div class="badge-pill inline-flex mb-3">
                        <i data-lucide="map-pin" class="size-3.5"></i>
                        {{ __('welcome.location.badge') }}
                    </div>
                    <h2 class="font-display font-semibold text-(--color-dark) dark:text-(--color-light)"
                        style="font-size: clamp(1.75rem, 4vw, 2.5rem);">
                        {{ __('welcome.location.title') }}
                    </h2>
                    <p class="mt-3 text-sm sm:text-base max-w-xl mx-auto" style="color: var(--color-dark-gray);">
                        {{ __('welcome.location.subtitle') }}
                    </p>
                </div>

                <div class="reveal" data-reveal>
                    <div class="rounded-2xl overflow-hidden shadow-xl border border-(--color-gray)/20 dark:border-(--color-dark-gray)/20"
                        style="aspect-ratio: 16/7; min-height: 300px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.2586949066567!2d111.8852425793457!3d-8.075076600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78e300192e2263%3A0xa9e9e49da6e81501!2sAndri%20Sablon%20Gedangsewu!5e0!3m2!1sen!2sid!4v1785880257392!5m2!1sen!2sid"
                            class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="strict-origin-when-cross-origin"
                            title="{{ __('welcome.location.iframe_title') }}">
                        </iframe>
                    </div>

                    {{-- Info strip --}}
                    <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-6 px-1"
                        style="color: var(--color-dark-gray);">
                        <span class="flex items-center gap-2 text-sm">
                            <i data-lucide="map-pin" class="size-4 text-(--color-primary) shrink-0"></i>
                            {{ __('welcome.location.address') }}
                        </span>
                        <a href="https://maps.app.goo.gl/andri-sablon" target="_blank" rel="noopener noreferrer"
                            class="flex items-center gap-1.5 text-sm font-medium text-(--color-primary) hover:underline">
                            <i data-lucide="external-link" class="size-3.5"></i>
                            {{ __('welcome.location.open_maps') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="py-8 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto">
                <hr class="stitch-line mb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-sm"
                    style="color: var(--color-dark-gray);">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-(--color-dark) dark:text-(--color-light)">AN Mastery</span>
                        <span>·</span>
                        <span>{{ __('welcome.footer.tagline') }}</span>
                    </div>
                    <p>{{ str_replace(':year', date('Y'), __('welcome.footer.copyright')) }}</p>
                </div>
            </div>
        </footer>

    </div>

    {{-- LIGHTBOX MODAL --}}
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/80 backdrop-blur-sm"
        @keydown.escape.window="modalOpen = false" @keydown.arrow-left.window="activeImages.length > 1 && prevImage()"
        @keydown.arrow-right.window="activeImages.length > 1 && nextImage()" x-cloak>

        <div class="relative max-w-4xl w-full bg-white dark:bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-gray-200 dark:border-slate-800"
            @click.away="modalOpen = false">

            <button type="button" @click="modalOpen = false"
                class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/50 hover:bg-black/75 text-white flex items-center justify-center transition-colors cursor-pointer">
                <i data-lucide="x" class="size-5"></i>
            </button>

            <div class="grid md:grid-cols-5 items-center">
                <div
                    class="md:col-span-3 relative bg-black flex items-center justify-center h-[45dvh] md:h-[70dvh] overflow-hidden">
                    <img :src="activeImages[activeIndex]" :alt="activeTitle"
                        class="max-w-full max-h-full w-auto h-auto object-contain" />

                    <template x-if="activeImages.length > 1">
                        <div>
                            <button type="button" @click="prevImage()"
                                class="absolute left-2 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center rounded-full
                                bg-white/15 backdrop-blur border border-white/20 text-white hover:bg-white/25 cursor-pointer">
                                <i data-lucide="chevron-left" class="size-5"></i>
                            </button>
                            <button type="button" @click="nextImage()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center rounded-full
                                bg-white/15 backdrop-blur border border-white/20 text-white hover:bg-white/25 cursor-pointer">
                                <i data-lucide="chevron-right" class="size-5"></i>
                            </button>
                            <span
                                class="absolute bottom-2.5 left-1/2 -translate-x-1/2 px-2.5 py-1 rounded-full bg-black/60 text-white text-[11px] font-semibold"
                                x-text="`${activeIndex + 1} / ${activeImages.length}`"></span>
                        </div>
                    </template>
                </div>
                <div class="md:col-span-2 p-6 sm:p-8 flex flex-col justify-center">
                    <span
                        class="inline-self-start px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider bg-(--color-primary)/15 text-(--color-primary) mb-3"
                        x-text="activeBadge"></span>
                    <h3 class="font-display font-semibold text-xl sm:text-2xl text-(--color-dark) dark:text-(--color-light) mb-2"
                        x-text="activeTitle"></h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed" x-text="activeSubtitle"></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('js/luicide-latest.js') }}"></script>
    <script>
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
        }, {
            threshold: 0.1
        });
        reveals.forEach(el => io.observe(el));
    </script>
    @RegisterServiceWorkerScript
</body>

</html>
