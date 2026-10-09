<div id="modal-global-script-terminal"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="global-terminal-title">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-3xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-neutral-950 border border-neutral-800 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden">
            
            <!-- Terminal Header -->
            <div class="p-3.5 bg-neutral-900/90 border-b border-neutral-800 flex items-center justify-between text-neutral-200">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex items-center gap-1.5 shrink-0">
                        <span class="size-3 rounded-full bg-red-500/80 inline-block shadow-xs"></span>
                        <span class="size-3 rounded-full bg-yellow-500/80 inline-block shadow-xs"></span>
                        <span class="size-3 rounded-full bg-emerald-500/80 inline-block shadow-xs"></span>
                    </div>
                    <span class="text-xs font-mono text-neutral-300 truncate ms-1.5" id="global-terminal-title">
                        deploy@an-mastery:~$ script.sh
                    </span>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span id="global-terminal-status" class="text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-800 text-neutral-400">
                        Ready
                    </span>
                    <button type="button" id="btn-clear-global-terminal"
                        title="{{ __('app_version.navbar_terminal_clear') }}"
                        class="p-1.5 rounded-lg text-neutral-400 hover:text-white hover:bg-neutral-800 transition-colors cursor-pointer">
                        <i data-lucide="eraser" class="size-3.5"></i>
                    </button>
                    <button type="button"
                        class="p-1.5 rounded-lg text-neutral-400 hover:text-white hover:bg-neutral-800 transition-colors cursor-pointer"
                        data-hs-overlay="#modal-global-script-terminal">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            </div>

            <!-- Terminal Screen / Output Body -->
            <div class="relative p-4 bg-neutral-950 flex-1 min-h-[260px] max-h-[65vh] overflow-hidden flex flex-col">
                <pre id="global-terminal-output"
                    class="w-full flex-1 p-3.5 bg-neutral-950 text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto overflow-y-auto rounded-xl border border-neutral-800/80 whitespace-pre-wrap select-text m-0 custom-scrollbar"></pre>
            </div>

            <!-- Terminal Footer -->
            <div class="p-3 px-5 bg-neutral-900/60 border-t border-neutral-800 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-[11px] text-neutral-400">
                    <i data-lucide="cpu" class="size-3.5 text-emerald-400"></i>
                    <span>Real-time stream via SSE &bull; Database SOT (.sh)</span>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" id="btn-rerun-global-script"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-2xs transition-all cursor-pointer">
                        <i data-lucide="rotate-cw" class="size-3.5"></i>
                        <span>{{ __('app_version.navbar_terminal_rerun') }}</span>
                    </button>
                    <button type="button"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-xl border border-neutral-700 text-neutral-300 hover:bg-neutral-800 transition-colors cursor-pointer"
                        data-hs-overlay="#modal-global-script-terminal">
                        {{ __('app_version.modal_btn_close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
