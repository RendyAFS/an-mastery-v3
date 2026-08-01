<nav
    class="sticky top-0 z-50 bg-(--color-light) dark:bg-(--color-dark) dark:border-b dark:border-(--color-gray)/20 shadow-sm">
    <div class="flex items-center justify-between px-4 py-3">
        <!-- Left -->
        <div class="flex items-center gap-3">
            <button type="button"
                class="lg:hidden flex justify-center items-center size-8 text-sm
                text-(--color-dark-gray)
                hover:bg-(--color-gray)/20 hover:text-(--color-primary)
                dark:text-(--color-gray)
                dark:hover:bg-(--color-gray)/20 dark:hover:text-(--color-secondary)
                rounded-lg focus:outline-none"
                aria-haspopup="dialog" data-hs-overlay="#hs-sidebar-content-push">

                <i data-lucide="panels-top-left" class="size-5"></i>
                <span class="sr-only">{{ __('navbar.Toggle Navigation') }}</span>
            </button>
        </div>

        <!-- Right -->
        <div class="flex items-center gap-5">
            {{-- Toggle Theme --}}
            @include('components.toggle-theme')

            <!-- Language Switch Dropdown -->
            <div class="hs-dropdown inline-flex">
                <button id="hs-dropdown-locale" type="button"
                    class="hs-dropdown-toggle inline-flex items-center gap-x-2 px-3 py-2
                    text-sm font-medium rounded-lg
                    hover:bg-(--color-gray)/20
                    dark:hover:bg-(--color-gray)/20
                    cursor-pointer">

                    <i data-lucide="globe" class="size-4 text-(--color-dark-gray)"></i>
                    <span class="text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ strtoupper(app()->getLocale()) }}
                    </span>
                    <i data-lucide="chevron-down"
                        class="hs-dropdown-open:rotate-180 size-4 transition-transform
                        text-(--color-dark-gray)"></i>
                </button>

                <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10
                    transition-[margin,opacity] opacity-0 duration-300
                    min-w-32 bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
                    shadow-md rounded-lg p-2"
                    role="menu">

                    <a href="{{ route('locale.switch', 'id') }}"
                        class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm
                        {{ app()->getLocale() === 'id' ? 'text-(--color-primary) font-semibold' : 'text-(--color-dark)' }}
                        dark:text-(--color-gray)
                        hover:bg-(--color-gray)/20">
                        Indonesia
                    </a>

                    <a href="{{ route('locale.switch', 'en') }}"
                        class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm
                        {{ app()->getLocale() === 'en' ? 'text-(--color-primary) font-semibold' : 'text-(--color-dark)' }}
                        dark:text-(--color-gray)
                        hover:bg-(--color-gray)/20">
                        English
                    </a>
                </div>
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
                    min-w-60 bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
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

                    <a href="{{ route('profile.index') }}"
                        class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm
                        text-(--color-dark)
                        dark:text-(--color-gray)
                        hover:bg-(--color-gray)/20">
                        <i data-lucide="user" class="size-4"></i>
                        {{ __('navbar.Profile') }}
                    </a>

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
