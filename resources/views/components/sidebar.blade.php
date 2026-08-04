<div id="hs-sidebar-content-push"
    class="group/sidebar hs-overlay [--auto-close:lg]
           hs-overlay-minified:w-14
           hs-overlay-minified:hover:w-65
           lg:block lg:translate-x-0 w-65
           hs-overlay-open:translate-x-0 -translate-x-full
           transition-all duration-300 transform
           h-full fixed top-0 inset-s-0 bottom-0 z-60
           overflow-hidden
           bg-(--color-light) dark:bg-(--color-dark)
           border-e border-(--color-gray)/20"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">
        <!-- Header -->
        <header class="p-4 flex items-center justify-between gap-x-2">
            <!-- Brand -->
            <a class="flex items-center gap-2 overflow-hidden hs-overlay-minified:group-hover/sidebar:flex hs-overlay-minified:hidden"
                href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/Logo-AnMastery.webp') }}"
                     alt="AN Mastery Logo"
                     class="h-9 w-auto object-contain flex-shrink-0">
                <span class="font-semibold text-base text-(--color-primary) dark:text-(--color-secondary) truncate">
                    AN Mastery
                </span>
            </a>

            <!-- Mobile Close -->
            <div class="lg:hidden">
                <button type="button"
                    class="flex items-center justify-center size-7 rounded-full border border-(--color-gray) text-(--color-dark-gray)"
                    data-hs-overlay="#hs-sidebar-content-push">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            <!-- Desktop Mini Toggle -->
            <div class="hidden lg:block">
                <button type="button"
                    class="flex items-center justify-center size-8 rounded-full text-(--color-dark-gray) hover:bg-(--color-gray)/20 cursor-pointer"
                    aria-label="Minify sidebar" data-hs-overlay-minifier="#hs-sidebar-content-push">

                    <!-- icon when full -->
                    <i data-lucide="panel-left-close" class="size-4 hs-overlay-minified:hidden"></i>

                    <!-- icon when mini -->
                    <i data-lucide="panel-left-open" class="size-4 hidden hs-overlay-minified:block"></i>
                </button>
            </div>
        </header>

        <!-- Body -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar">
            <ul class="px-2 py-4 space-y-1.5">
                @foreach ($menus as $menu)
                    @php
                        $isOpen = $menu->children->contains(function ($child) {
                            return request()->is(trim($child->url, '/') . '*');
                        });
                    @endphp
                    @if ($menu->children->isEmpty())
                        <li>
                            <a href="{{ $menu->url }}"
                                class="flex items-center gap-x-3.5 py-3 px-3 rounded-xl text-sm transition duration-300 ease-in-out
                               text-(--color-dark-gray) dark:text-(--color-light) font-semibold cursor-pointer
                               hover:bg-(--color-gray)/50 hover:text-(--color-primary) dark:hover:text-(--color-secondary)
                               {{ request()->is(trim($menu->url, '/') . '*') ? 'bg-(--color-primary) text-(--color-light)' : '' }}">

                                <i data-lucide="{{ $menu->icon }}" class="size-4"></i>
                                <span
                                    class="hs-overlay-minified:group-hover/sidebar:block
                                    hs-overlay-minified:hidden">
                                    {{ __('sidebar.' . $menu->name) }}
                                </span>
                            </a>
                        </li>
                    @else
                        <li class="hs-accordion {{ $isOpen ? 'hs-accordion-active' : '' }}">
                            <button type="button"
                                class="hs-accordion-toggle w-full flex items-center gap-x-3.5 py-3 px-3 rounded-xl text-sm
                                font-semibold cursor-pointer transition duration-300 ease-in-out
                                 text-(--color-dark-gray) dark:text-(--color-light)
                                hover:bg-(--color-gray)/50 hover:text-(--color-primary) dark:hover:text-(--color-secondary)">

                                <i data-lucide="{{ $menu->icon }}" class="size-4"></i>
                                <span
                                    class="hs-overlay-minified:group-hover/sidebar:block hs-overlay-minified:hidden">
                                    {{ __('sidebar.' . $menu->name) }}
                                </span>

                                <i data-lucide="chevron-down"
                                    class="ms-auto size-4 transition-transform duration-300
                                    hs-accordion-active:rotate-180
                                    hs-overlay-minified:group-hover/sidebar:block
                                    hs-overlay-minified:hidden"></i>
                            </button>

                            <div
                                class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ $isOpen ? '' : 'hidden' }}">
                                <ul
                                    class="mt-1 ps-7 space-y-1 hs-overlay-minified:group-hover/sidebar:block
                                        hs-overlay-minified:hidden">
                                    @foreach ($menu->children as $child)
                                        @php
                                            $childPermissions = $child->permissions->pluck('name')->toArray();
                                        @endphp

                                        @if (empty($childPermissions) || auth()->user()->canAny($childPermissions))
                                            <li>
                                                <a href="{{ $child->url }}"
                                                    class="block py-2 px-3 rounded-xl text-sm transition duration-300 ease-in-out font-semibold
                                                    hover:bg-(--color-gray)/50 hover:text-(--color-primary) dark:hover:text-(--color-secondary)
                                                    {{ request()->is(trim($child->url, '/') . '*')
                                                        ? 'bg-(--color-primary) text-(--color-light)'
                                                        : 'text-(--color-dark-gray) dark:text-(--color-light)' }}">
                                                    {{ __('sidebar.' . $child->name) }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach

                                </ul>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</div>
