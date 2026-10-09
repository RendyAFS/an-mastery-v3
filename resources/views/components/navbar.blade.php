<nav
    class="sticky top-0 z-50 bg-(--color-light) dark:bg-(--color-dark) dark:border-b dark:border-(--color-gray)/20 shadow-sm">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left -->
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" id="navbar-logo" class="hidden items-center gap-2 overflow-hidden">
                <img src="{{ asset('assets/Logo-AnMastery.webp') }}" alt="AN Mastery Logo"
                    class="h-8 w-auto object-contain shrink-0">
                <div class="flex flex-col leading-tight">
                    <span class="font-semibold text-base text-(--color-primary) dark:text-(--color-secondary) truncate">
                        AN Mastery
                    </span>
                    <span class="text-[10px] text-(--color-dark-gray) dark:text-(--color-gray) truncate">
                        v{{ config('app.version', '1.0.0') }}
                    </span>
                </div>
            </a>
        </div>

        <!-- Center: App Version Update Notification -->
        <div id="navbar-version-checker-container" class="hidden items-center justify-center mx-2 animate-fade-in">
            <button type="button"
                data-hs-overlay="#modal-app-update"
                id="btn-navbar-version-update"
                class="group relative inline-flex items-center gap-2 px-3 py-1 sm:px-3.5 sm:py-1.5 rounded-full
                       bg-gradient-to-r from-(--color-primary)/15 via-emerald-500/15 to-(--color-primary)/20
                       hover:from-(--color-primary)/25 hover:to-emerald-500/25
                       border border-(--color-primary)/40 hover:border-(--color-primary)
                       text-(--color-primary) dark:text-emerald-400
                       text-xs font-semibold shadow-2xs hover:shadow-xs
                       transition-all duration-300 cursor-pointer active:scale-95"
                title="{{ __('app_version.modal_update_title') }}">
                <span class="relative flex size-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full size-2 bg-emerald-500"></span>
                </span>
                <span class="flex items-center gap-1 min-w-0">
                    <i data-lucide="sparkles" class="size-3.5 shrink-0 text-(--color-primary) dark:text-emerald-400 animate-pulse"></i>
                    <span id="navbar-update-text" class="truncate font-medium">Update Tersedia</span>
                </span>
                <i data-lucide="chevron-right" class="size-3.5 shrink-0 text-(--color-primary) group-hover:translate-x-0.5 transition-transform"></i>
            </button>
        </div>

        <!-- Right -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Workshop Switcher Button (Tablet & Desktop) -->
            <button type="button"
                data-hs-overlay="#modal-switch-workshop"
                class="hidden sm:inline-flex items-center gap-2 px-2.5 py-1.5 rounded-lg border border-(--color-gray)/20 hover:border-(--color-primary)/50 bg-(--color-light-gray)/60 dark:bg-(--color-dark-slate)/80 hover:bg-(--color-primary)/10 text-(--color-dark) dark:text-(--color-light) transition-all cursor-pointer group shadow-2xs"
                title="{{ __('workshop.switch_workshop') }}">
                <div class="size-7 rounded-md bg-(--color-primary)/10 dark:bg-(--color-primary)/20 text-(--color-primary) flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                    <i data-lucide="store" class="size-4"></i>
                </div>
                <div class="flex flex-col text-left leading-tight max-w-[140px] md:max-w-[220px]">
                    <span class="text-[9px] text-(--color-dark-gray) dark:text-(--color-gray) uppercase tracking-wider font-semibold">
                        {{ __('models.Workshop') }}
                    </span>
                    <span class="text-xs font-semibold text-(--color-dark) dark:text-(--color-light) truncate">
                        {{ $user?->workshop?->name ?? __('workshop.no_workshop') }}
                    </span>
                </div>
                <i data-lucide="chevrons-up-down" class="size-3.5 text-(--color-dark-gray) dark:text-(--color-gray) shrink-0 opacity-70 group-hover:opacity-100 transition-opacity"></i>
            </button>

            {{-- Toggle Theme (desktop only) --}}
            <div class="hidden lg:block">
                @include('components.toggle-theme')
            </div>

            <!-- Language Switch Dropdown (desktop only) -->
            <div class="hidden lg:block">
                @include('components.toggle-language')
            </div>



            <!-- Profile Dropdown -->
            <div class="hs-dropdown inline-flex">
                <button id="hs-dropdown-profile" type="button"
                    class="hs-dropdown-toggle inline-flex items-center gap-x-3 px-3 py-2
                    text-sm font-medium rounded-lg
                    hover:bg-(--color-gray)/20
                    dark:hover:bg-(--color-gray)/20
                    cursor-pointer">

                    {{-- Avatar --}}
                    <img src="{{ $user->getFirstMediaUrl('user-profile') ?: 'https://ui-avatars.com/api/?background=random&name=' . $user->name }}"
                        class="inline-block size-8 rounded-full ring-2 ring-(--color-primary)">

                    <div class="hidden sm:block text-left">
                        <p
                            class="text-sm font-semibold
                            text-(--color-dark)
                            dark:text-(--color-gray)">
                            {{ $user->name }}
                        </p>
                        <p
                            class="text-xs
                            text-(--color-dark-gray)
                            dark:text-(--color-gray)">
                            {{ $user->getRoleNames()->implode(', ') ?? __('navbar.Unknown') }}
                        </p>
                    </div>

                    <i data-lucide="chevron-down"
                        class="hs-dropdown-open:rotate-180 size-4 transition-transform
                        text-(--color-dark-gray)"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10
                    transition-[margin,opacity] opacity-0 duration-300
                    min-w-60 max-h-[80vh] overflow-y-auto custom-scrollbar
                    bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
                    shadow-md rounded-lg p-2"
                    role="menu">

                    <div class="px-3 py-2">
                        <p
                            class="text-sm font-semibold
                            text-(--color-dark)
                            dark:text-(--color-light)">
                            {{ $user->name }}
                        </p>
                        <p
                            class="text-xs
                            text-(--color-dark-gray)
                            dark:text-(--color-gray)">
                            {{ $user->email }}
                        </p>
                    </div>

                    {{-- Workshop Switcher (mobile only in dropdown) --}}
                    <div class="sm:hidden border-t border-(--color-gray)/20 my-1 py-1">
                        <div class="px-3 py-1">
                            <p class="text-[11px] font-semibold uppercase tracking-wide text-(--color-dark-gray) dark:text-(--color-gray)">
                                {{ __('models.Workshop') }}
                            </p>
                            <button type="button"
                                data-hs-overlay="#modal-switch-workshop"
                                class="w-full flex items-center justify-between gap-2 mt-1.5 p-2 rounded-lg bg-(--color-primary)/10 border border-(--color-primary)/20 text-(--color-primary) hover:bg-(--color-primary)/20 transition-colors text-left cursor-pointer">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i data-lucide="store" class="size-4 shrink-0"></i>
                                    <span class="text-xs font-semibold truncate">
                                        {{ $user?->workshop?->name ?? __('workshop.no_workshop') }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-medium underline shrink-0">{{ __('workshop.switch_workshop') }}</span>
                            </button>
                        </div>
                    </div>

                    {{-- Theme & Language (mobile only) --}}
                    <div class="lg:hidden border-t border-b border-(--color-gray)/20 my-1 py-1 space-y-2">
                        <div class="px-3 py-1">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wide text-(--color-dark-gray) dark:text-(--color-gray)">
                                {{ __('navbar.Theme') }}
                            </p>
                            <div class="grid grid-cols-3 gap-1 mt-1.5">
                                <button type="button"
                                    class="flex flex-col items-center gap-1 py-2 rounded-lg text-[11px] cursor-pointer
                                    text-(--color-dark) hover:bg-(--color-gray)/20
                                    dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20"
                                    data-hs-theme-click-value="light">
                                    <i data-lucide="sun" class="size-4"></i>
                                    {{ __('navbar.Light') }}
                                </button>
                                <button type="button"
                                    class="flex flex-col items-center gap-1 py-2 rounded-lg text-[11px] cursor-pointer
                                    text-(--color-dark) hover:bg-(--color-gray)/20
                                    dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20"
                                    data-hs-theme-click-value="dark">
                                    <i data-lucide="moon" class="size-4"></i>
                                    {{ __('navbar.Dark') }}
                                </button>
                                <button type="button"
                                    class="flex flex-col items-center gap-1 py-2 rounded-lg text-[11px] cursor-pointer
                                    text-(--color-dark) hover:bg-(--color-gray)/20
                                    dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20"
                                    data-hs-theme-click-value="auto">
                                    <i data-lucide="monitor-cog" class="size-4"></i>
                                    {{ __('navbar.Auto') }}
                                </button>
                            </div>
                        </div>

                        <div class="px-3 py-1">
                            <p
                                class="text-[11px] font-semibold uppercase tracking-wide text-(--color-dark-gray) dark:text-(--color-gray)">
                                {{ __('navbar.Language') }}
                            </p>
                            <div class="grid grid-cols-2 gap-1 mt-1.5">
                                <a href="{{ route('locale.switch', 'id') }}"
                                    class="flex items-center justify-center gap-x-2 py-2 rounded-lg text-xs
                                    {{ app()->getLocale() === 'id' ? 'text-(--color-primary) font-semibold bg-(--color-primary)/10' : 'text-(--color-dark) dark:text-(--color-gray) hover:bg-(--color-gray)/20' }}">
                                    Indonesia
                                </a>
                                <a href="{{ route('locale.switch', 'en') }}"
                                    class="flex items-center justify-center gap-x-2 py-2 rounded-lg text-xs
                                    {{ app()->getLocale() === 'en' ? 'text-(--color-primary) font-semibold bg-(--color-primary)/10' : 'text-(--color-dark) dark:text-(--color-gray) hover:bg-(--color-gray)/20' }}">
                                    English
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('profile.index') }}"
                        class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                        text-(--color-dark)
                        dark:text-(--color-gray)
                        hover:bg-(--color-gray)/20">
                        <i data-lucide="user" class="size-4"></i>
                        {{ __('navbar.Profile') }}
                    </a>

                    <button type="button"
                        data-hs-overlay="#modal-switch-workshop"
                        class="hidden sm:flex w-full items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-(--color-dark) dark:text-(--color-gray) hover:bg-(--color-gray)/20 cursor-pointer">
                        <i data-lucide="store" class="size-4"></i>
                        {{ __('workshop.switch_workshop') }}
                    </button>

                    @if (auth()->user()->can('app-version.view'))
                        <a href="{{ route('app_version.index') }}"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                            text-(--color-dark)
                            dark:text-(--color-gray)
                            hover:bg-(--color-gray)/20">
                            <i data-lucide="git-branch" class="size-4"></i>
                            {{ __('app_version.title') }}
                        </a>
                    @endif

                    @if (auth()->user()->can('dashboard.log-viewer'))
                        <a href="{{ route('log-viewer.index') }}" target="_blank"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                            text-(--color-dark)
                            dark:text-(--color-gray)
                            hover:bg-(--color-gray)/20">
                            <i data-lucide="database" class="size-4"></i>
                            {{ __('navbar.Log Viewer') }}
                        </a>

                        <a href="{{ route('cert.download') }}" target="_blank"
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                            text-(--color-dark)
                            dark:text-(--color-gray)
                            hover:bg-(--color-gray)/20">
                            <i data-lucide="shield-check" class="size-4"></i>
                            {{ __('navbar.Download Cert') }}
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                            text-(--color-red) cursor-pointer
                            hover:bg-(--color-red)/10">
                            <i data-lucide="log-out" class="size-4"></i>
                            {{ __('navbar.Logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

@include('components.partials._modal-switch-workshop')
@include('components.partials._modal-app-update')






