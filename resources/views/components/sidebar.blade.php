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
                    class="h-9 w-auto object-contain shrink-0">
                <span class="font-semibold text-base text-(--color-primary) dark:text-(--color-secondary) truncate">
                    AN Mastery
                </span>
            </a>
        </header>

        <div class="px-3 pb-3">
            <button type="button" id="sidebar-mode-switch"
                class="group/mode-btn relative w-full flex items-center gap-x-3 py-2.5 px-3 rounded-2xl
                bg-(--color-primary)/10 hover:bg-(--color-primary)/15
                border border-(--color-primary)/20
                transition-all duration-300 cursor-pointer
                hs-overlay-minified:justify-center hs-overlay-minified:px-0">
                <span
                    class="flex items-center justify-center size-8 rounded-full shrink-0
                    bg-(--color-primary) text-(--color-light) shadow-sm shadow-(--color-primary)/40
                    transition-transform duration-300 group-hover/mode-btn:scale-105">
                    <i data-lucide="app-window" class="size-4"></i>
                </span>
                <span
                    class="flex flex-col items-start text-start min-w-0
                    hs-overlay-minified:group-hover/sidebar:flex hs-overlay-minified:hidden">
                    <span class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) truncate">
                        {{ __('sidebar.Floating Menu') }}
                    </span>
                    <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) truncate">
                        {{ __('sidebar.Switch display mode') }}
                    </span>
                </span>
                <i data-lucide="chevron-right"
                    class="ms-auto size-4 shrink-0 text-(--color-primary)
                    transition-transform duration-300 group-hover/mode-btn:translate-x-0.5
                    hs-overlay-minified:group-hover/sidebar:block hs-overlay-minified:hidden"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar px-2 py-4">
            <x-sidebar-menu :menus="$menus" />
        </nav>
    </div>
</div>

<button type="button" id="sidebar-floating-trigger"
    class="hidden fixed bottom-6 inset-e-6 z-70 size-14 rounded-full
           bg-(--color-primary) text-(--color-light) shadow-lg
           items-center justify-center
           hover:brightness-110 active:scale-95 transition-all cursor-pointer"
    aria-haspopup="dialog" data-hs-overlay="#sidebar-floating-modal" aria-label="{{ __('sidebar.Open Menu') }}">
    <i data-lucide="menu" class="size-6"></i>
</button>

<x-modal id="sidebar-floating-modal" :title="__('sidebar.Menu')" size="sm">
    <button type="button" id="sidebar-back-to-mode"
        class="hidden lg:flex group/mode-btn relative w-full items-center gap-x-3 py-2.5 px-3 rounded-2xl
        bg-(--color-primary)/10 hover:bg-(--color-primary)/15
        border border-(--color-primary)/20
        transition-all duration-300 cursor-pointer">
        <span
            class="flex items-center justify-center size-8 rounded-full shrink-0
            bg-(--color-primary) text-(--color-light) shadow-sm shadow-(--color-primary)/40
            transition-transform duration-300 group-hover/mode-btn:scale-105">
            <i data-lucide="panel-left" class="size-4"></i>
        </span>
        <span class="flex flex-col items-start text-start min-w-0">
            <span class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) truncate">
                {{ __('sidebar.Back to Sidebar') }}
            </span>
            <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) truncate">
                {{ __('sidebar.Switch display mode') }}
            </span>
        </span>
        <i data-lucide="chevron-right"
            class="ms-auto size-4 shrink-0 text-(--color-primary)
            transition-transform duration-300 group-hover/mode-btn:translate-x-0.5"></i>
    </button>

    <x-sidebar-menu :menus="$menus" />
</x-modal>
