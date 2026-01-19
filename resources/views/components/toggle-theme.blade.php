<div class="hs-dropdown">
    <button id="hs-dropdown-dark-mode" type="button"
        class="hs-dropdown-toggle hs-dark-mode group flex items-center text-(--color-gray) hover:text-(--color-primary)
        focus:outline-hidden focus:text-(--color-primary)
        font-medium dark:text-(--color-gray) dark:hover:text-(--color-gray) dark:focus:text-(--color-gray)"
        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
        <svg class="hs-dark-mode-active:hidden block size-5" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"></path>
        </svg>
        <svg class="hs-dark-mode-active:block hidden size-5" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="4"></circle>
            <path d="M12 2v2"></path>
            <path d="M12 20v2"></path>
            <path d="m4.93 4.93 1.41 1.41"></path>
            <path d="m17.66 17.66 1.41 1.41"></path>
            <path d="M2 12h2"></path>
            <path d="M20 12h2"></path>
            <path d="m6.34 17.66-1.41 1.41"></path>
            <path d="m19.07 4.93-1.41 1.41"></path>
        </svg>
    </button>

    <div id="selectThemeDropdown"
        class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10 transition-[margin,opacity] opacity-0 duration-300 mb-2 origin-bottom-left bg-(--color-light) shadow-md rounded-lg p-1 space-y-0.5 dark:bg-(--color-dark) dark:border dark:border-(--color-dark) dark:divide-(--color-dark)"
        role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-dark-mode">
        <button type="button"
            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-(color-dark) hover:bg-(--color-gray)/20 focus:outline-hidden focus:bg-(--color-light) dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20 dark:hover:text-(--color-gray) dark:focus:bg-(--color-dark) dark:focus:text-(--color-gray)"
            data-hs-theme-click-value="light">
            Light
        </button>
        <button type="button"
            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-(color-dark) hover:bg-(--color-gray)/20 focus:outline-hidden focus:bg-(--color-light) dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20 dark:hover:text-(--color-gray) dark:focus:bg-(--color-dark) dark:focus:text-(--color-gray)"
            data-hs-theme-click-value="dark">
            Dark
        </button>
        <button type="button"
            class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-(color-dark) hover:bg-(--color-gray) focus:outline-hidden focus:bg-(--color-light) dark:text-(--color-gray) dark:hover:bg-(--color-gray)/20 dark:hover:text-(--color-gray) dark:focus:bg-(--color-dark) dark:focus:text-(--color-gray)"
            data-hs-theme-click-value="auto">
            Auto (System)
        </button>
    </div>
</div>
