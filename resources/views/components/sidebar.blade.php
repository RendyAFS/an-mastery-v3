<div id="hs-sidebar-content-push"
    class="hs-overlay
           [--auto-close:lg]
           hs-overlay-minified:w-14
           lg:block lg:translate-x-0
           w-64
           hs-overlay-open:translate-x-0
           -translate-x-full
           transition-all duration-300 transform
           h-full fixed top-0 start-0 bottom-0 z-60
           bg-(--color-light) dark:bg-(--color-dark)
           border-e border-(--color-gray)/20"
    role="dialog" tabindex="-1" aria-label="Sidebar">

    <div class="relative flex flex-col h-full max-h-full">

        <!-- Header -->
        <header class="p-4 flex items-center justify-between gap-x-2">

            <!-- Brand -->
            <a class="font-semibold text-xl text-(--color-primary) dark:text-(--color-secondary) hs-overlay-minified:hidden"
                href="{{ route('dashboard') }}">
                AN Mastery
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
                    class="flex items-center justify-center size-8 rounded-full text-(--color-dark-gray) hover:bg-(--color-gray)/20"
                    aria-label="Minify sidebar" data-hs-overlay-minifier="#hs-sidebar-content-push">

                    <!-- icon when full -->
                    <i data-lucide="panel-left-close" class="size-4 hs-overlay-minified:hidden"></i>

                    <!-- icon when mini -->
                    <i data-lucide="panel-left-open" class="size-4 hidden hs-overlay-minified:block"></i>
                </button>
            </div>
        </header>

        <!-- Body -->
        <nav class="h-full overflow-y-auto custom-scrollbar">
            <ul class="px-2 py-4 space-y-1">

                <!-- Dashboard -->
                <li>
                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm bg-(--color-primary) text-(--color-light)">

                        <i data-lucide="home" class="size-4"></i>

                        <span class="hs-overlay-minified:hidden">
                            Dashboard
                        </span>
                    </a>
                </li>

                <!-- Users -->
                <li class="hs-accordion" id="users-accordion">
                    <button type="button"
                        class="hs-accordion-toggle w-full flex items-center gap-x-3.5
                               py-2 px-3 text-sm rounded-lg
                               text-(--color-dark-gray)
                               hover:bg-(--color-light-gray)">

                        <i data-lucide="users" class="size-4"></i>

                        <span class="hs-overlay-minified:hidden">
                            Users
                        </span>

                        <i data-lucide="chevron-down"
                            class="ms-auto size-4 transition-transform
                                  hs-accordion-active:rotate-180
                                  hs-overlay-minified:hidden"></i>
                    </button>

                    <div class="hs-accordion-content hidden">
                        <ul class="mt-1 ps-7 space-y-1 hs-overlay-minified:hidden">
                            @foreach (['All Users', 'Add User', 'Roles'] as $item)
                                <li>
                                    <a
                                        class="block py-2 px-3 rounded-lg text-sm
                                               text-(--color-dark-gray)
                                               hover:bg-(--color-light-gray)
                                               hover:text-(--color-primary)">
                                        {{ $item }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

            </ul>
        </nav>

    </div>
</div>
