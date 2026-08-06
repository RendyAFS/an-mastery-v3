<div id="hs-sidebar-content-push"
    class="hidden lg:block group/sidebar hs-overlay
           hs-overlay-minified:w-14
           hs-overlay-minified:hover:w-65
           w-65
           transition-all duration-300
           h-full fixed top-0 inset-s-0 bottom-0 z-60
           overflow-hidden
           bg-(--color-light) dark:bg-(--color-dark)
           border-e border-(--color-gray)/20"
    role="navigation" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">
        <header class="p-4 flex items-center justify-between gap-x-2">
            <a class="flex items-center gap-2 overflow-hidden hs-overlay-minified:group-hover/sidebar:flex hs-overlay-minified:hidden"
                href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/Logo-AnMastery.webp') }}" alt="AN Mastery Logo"
                    class="h-9 w-auto object-contain flex-shrink-0">
                <span class="font-semibold text-base text-(--color-primary) dark:text-(--color-secondary) truncate">
                    AN Mastery
                </span>
            </a>

            <div class="hidden lg:block">
                <button type="button"
                    class="flex items-center justify-center size-8 rounded-full text-(--color-dark-gray) hover:bg-(--color-gray)/20 cursor-pointer"
                    aria-label="Minify sidebar" data-hs-overlay-minifier="#hs-sidebar-content-push">
                    <i data-lucide="panel-left-close" class="size-4 hs-overlay-minified:hidden"></i>
                    <i data-lucide="panel-left-open" class="size-4 hidden hs-overlay-minified:block"></i>
                </button>
            </div>
        </header>

        <div class="px-4 pb-3">
            <button type="button" id="sidebar-mode-switch"
                class="w-full flex items-center justify-center gap-x-2 py-2 px-3.5 rounded-full text-sm font-semibold
                text-(--color-danger) border border-(--color-danger)/30
                hover:bg-(--color-danger)/10 transition-all duration-300 cursor-pointer">
                <i data-lucide="circle-dot" class="size-4 shrink-0"></i>
                <span class="hs-overlay-minified:group-hover/sidebar:inline hs-overlay-minified:hidden truncate">
                    {{ __('sidebar.Floating Menu') }}
                </span>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar px-2 py-4">
            <x-sidebar-menu :menus="$menus" />
        </nav>
    </div>
</div>

<button type="button" id="sidebar-floating-trigger"
    class="hidden fixed bottom-6 end-6 z-70 size-14 rounded-full
           bg-(--color-primary) text-(--color-light) shadow-lg
           items-center justify-center
           hover:brightness-110 active:scale-95 transition-all cursor-pointer"
    aria-haspopup="dialog" data-hs-overlay="#sidebar-floating-modal" aria-label="{{ __('sidebar.Open Menu') }}">
    <i data-lucide="menu" class="size-6"></i>
</button>

<x-modal id="sidebar-floating-modal" :title="__('sidebar.Menu')" size="sm">
    <button type="button" id="sidebar-back-to-mode"
        class="hidden lg:flex w-full items-center justify-center gap-x-2 py-2 px-3.5 rounded-full text-sm font-semibold
        text-(--color-danger) border border-(--color-danger)/30
        hover:bg-(--color-danger)/10 transition-all duration-300 cursor-pointer">
        <i data-lucide="panel-left-open" class="size-4"></i>
        {{ __('sidebar.Back to Sidebar') }}
    </button>

    <x-sidebar-menu :menus="$menus" />
</x-modal>
