@php
    $deploymentScripts = app(\App\Services\AppVersionService::class)->getDeploymentScripts();
@endphp

<div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
    <button id="hs-dropdown-navbar-terminal" type="button"
        class="hs-dropdown-toggle inline-flex items-center justify-center size-9 rounded-lg
               border border-(--color-gray)/20 hover:border-(--color-primary)/50
               bg-(--color-light-gray)/60 dark:bg-(--color-dark-slate)/80
               hover:bg-(--color-primary)/10 text-(--color-dark) dark:text-(--color-light)
               transition-all cursor-pointer group shadow-2xs"
        title="{{ __('app_version.navbar_terminal_title') }}"
        aria-label="{{ __('app_version.navbar_terminal_title') }}">
        <i data-lucide="terminal" class="size-4 text-(--color-primary) group-hover:scale-110 transition-transform"></i>
    </button>

    <!-- Dropdown Menu -->
    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-60
                transition-[margin,opacity] opacity-0 duration-200
                w-80 sm:w-96 max-h-[85vh] overflow-y-auto custom-scrollbar
                bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20
                shadow-xl rounded-2xl p-0 overflow-hidden"
        role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-navbar-terminal">
        
        <!-- Header -->
        <div class="p-3.5 bg-gradient-to-r from-(--color-primary)/15 via-emerald-500/10 to-transparent border-b border-(--color-gray)/15 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="size-7 rounded-lg bg-(--color-primary)/20 text-(--color-primary) flex items-center justify-center">
                    <i data-lucide="terminal" class="size-4"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-(--color-dark) dark:text-(--color-light) leading-tight">
                        {{ __('app_version.navbar_terminal_title') }}
                    </h4>
                    <p class="text-[10px] text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.navbar_terminal_desc') }}
                    </p>
                </div>
            </div>
            <span class="text-[9px] font-mono font-bold px-2 py-0.5 rounded-full bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                SOT (.sh)
            </span>
        </div>

        <!-- Script List -->
        <div class="p-2 space-y-1.5 max-h-72 overflow-y-auto custom-scrollbar divide-y divide-(--color-gray)/10">
            @forelse($deploymentScripts as $script)
                <div class="p-2.5 rounded-xl hover:bg-(--color-light-gray)/60 dark:hover:bg-(--color-dark-slate)/60 transition-colors flex items-center justify-between gap-3 group">
                    <div class="flex items-start gap-2.5 min-w-0">
                        <div class="size-8 rounded-lg bg-(--color-primary)/10 text-(--color-primary) flex items-center justify-center shrink-0 mt-0.5 group-hover:bg-(--color-primary) group-hover:text-white transition-colors">
                            <i data-lucide="{{ $script['icon'] ?? 'terminal' }}" class="size-4"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="text-xs font-semibold text-(--color-dark) dark:text-(--color-light) truncate">
                                    {{ $script['name'] }}
                                </span>
                            </div>
                            <p class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) line-clamp-1 mt-0.5">
                                {{ $script['description'] ?? '' }}
                            </p>
                            <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400">
                                {{ $script['key'] }}
                            </span>
                        </div>
                    </div>

                    <button type="button"
                        class="btn-run-global-script shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold bg-(--color-primary)/15 hover:bg-(--color-primary) text-(--color-primary) hover:text-white transition-all cursor-pointer shadow-2xs hover:shadow-xs active:scale-95"
                        data-script-key="{{ $script['key'] }}"
                        data-script-name="{{ $script['name'] }}"
                        title="{{ __('app_version.navbar_terminal_run') }} {{ $script['name'] }}">
                        <i data-lucide="play" class="size-3"></i>
                        <span>{{ __('app_version.navbar_terminal_run') }}</span>
                    </button>
                </div>
            @empty
                <div class="p-4 text-center text-xs text-(--color-dark-gray) dark:text-(--color-gray)">
                    {{ __('app_version.navbar_terminal_empty') }}
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="p-2.5 bg-(--color-light-gray)/40 dark:bg-(--color-dark-slate)/40 border-t border-(--color-gray)/15 flex items-center justify-between">
            <a href="{{ route('app_version.index') }}#scripts-section"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-(--color-primary) hover:underline">
                <i data-lucide="sliders" class="size-3.5"></i>
                <span>{{ __('app_version.navbar_terminal_manage') }}</span>
            </a>

            <button type="button"
                data-hs-overlay="#modal-global-script-terminal"
                class="inline-flex items-center gap-1 text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) hover:text-(--color-dark) dark:hover:text-(--color-light) cursor-pointer">
                <i data-lucide="terminal-square" class="size-3.5"></i>
                <span>Console</span>
            </button>
        </div>
    </div>
</div>
