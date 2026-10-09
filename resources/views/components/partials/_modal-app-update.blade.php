<div id="modal-app-update"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="modal-app-update-label">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-300 mt-0 opacity-0 ease-out transition-all sm:max-w-xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div class="w-full flex flex-col bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/20 shadow-2xl rounded-2xl pointer-events-auto overflow-hidden">
            <!-- Modal Header -->
            <div class="relative bg-gradient-to-r from-(--color-primary)/15 via-emerald-500/10 to-(--color-primary)/5 p-5 border-b border-(--color-gray)/15">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="size-10 rounded-xl bg-(--color-primary) text-(--color-light) flex items-center justify-center shadow-md shadow-(--color-primary)/30">
                            <i data-lucide="rocket" class="size-5"></i>
                        </div>
                        <div>
                            <h3 id="modal-app-update-label" class="font-bold text-base text-(--color-dark) dark:text-(--color-light) leading-snug">
                                {{ __('app_version.modal_update_title') }}
                            </h3>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray)" id="modal-update-subtitle">
                                {{ __('app_version.modal_update_subtitle_default') }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center rounded-lg text-(--color-dark-gray) hover:text-(--color-dark) hover:bg-(--color-gray)/20 dark:hover:text-(--color-light) focus:outline-hidden cursor-pointer"
                        data-hs-overlay="#modal-app-update">
                        <span class="sr-only">{{ __('app_version.modal_btn_close') }}</span>
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                <!-- Version Comparison Pill -->
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-(--color-light-gray)/60 dark:bg-(--color-dark-slate)/60 border border-(--color-gray)/20">
                    <div>
                        <span class="text-[10px] uppercase font-semibold text-(--color-dark-gray) dark:text-(--color-gray) block">
                            {{ __('app_version.modal_curr_version') }}
                        </span>
                        <span class="text-sm font-mono font-bold text-(--color-dark) dark:text-(--color-light)" id="modal-curr-version">
                            v{{ \App\Models\AppSetting::get('app_version') ?: (config('app.version') ?: '1.0.0') }}
                        </span>
                    </div>
                    <i data-lucide="arrow-right" class="size-4 text-(--color-primary) shrink-0"></i>
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-semibold text-(--color-dark-gray) dark:text-(--color-gray) block">
                            {{ __('app_version.modal_new_version') }}
                        </span>
                        <span class="text-sm font-mono font-bold text-emerald-600 dark:text-emerald-400" id="modal-new-version">
                            -
                        </span>
                    </div>
                </div>

                <!-- Release Title & Published info -->
                <div id="modal-release-info-container" class="hidden">
                    <h4 class="text-sm font-bold text-(--color-dark) dark:text-(--color-light)" id="modal-release-name">
                        Release Note
                    </h4>
                    <p class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) mt-0.5" id="modal-published-meta">
                        -
                    </p>
                </div>

                <!-- Changelog Box -->
                <div id="modal-changelog-container" class="space-y-1.5 hidden">
                    <span class="text-xs font-semibold uppercase tracking-wider text-(--color-dark-gray) dark:text-(--color-gray)">
                        {{ __('app_version.changelog') }}
                    </span>
                    <div id="modal-changelog-content" class="p-3.5 rounded-xl bg-(--color-light-gray) dark:bg-(--color-dark-slate) text-xs text-(--color-dark) dark:text-(--color-light) whitespace-pre-wrap font-sans border border-(--color-gray)/15 leading-relaxed">
                    </div>
                </div>

                <!-- Live Auto-Update Section (Super Admin / Deployment Execution) -->
                <div class="p-4 rounded-xl bg-gradient-to-br from-emerald-500/10 via-(--color-primary)/10 to-transparent border border-emerald-500/20 space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-xs font-bold text-(--color-dark) dark:text-(--color-light) flex items-center gap-1.5">
                                <i data-lucide="zap" class="size-4 text-emerald-500"></i>
                                {{ __('app_version.modal_auto_update_title') }}
                            </span>
                            <p class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray) mt-0.5">
                                {{ __('app_version.modal_auto_update_desc', ['script' => 'update_project']) }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" id="btn-run-modal-backup"
                                title="Backup Database Sebelum Update"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold
                                       bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)/30
                                       hover:border-(--color-primary) hover:text-(--color-primary)
                                       text-(--color-dark) dark:text-(--color-light) shadow-2xs transition-all cursor-pointer">
                                <i data-lucide="database" class="size-3.5 text-blue-500"></i>
                                <span>Backup DB</span>
                            </button>
                            <button type="button" id="btn-run-auto-update"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold
                                       bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm shadow-emerald-600/30
                                       hover:scale-102 active:scale-98 transition-all cursor-pointer">
                                <i data-lucide="play" class="size-3.5" id="icon-run-update"></i>
                                <span id="text-run-update">{{ __('app_version.modal_btn_run_update') }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Live Terminal Output Console -->
                    <div id="modal-terminal-wrapper" class="hidden space-y-2 pt-2 border-t border-(--color-gray)/15 animate-fade-in">
                        <div class="flex items-center justify-between px-3 py-1.5 bg-neutral-900 rounded-t-xl border-b border-neutral-800">
                            <div class="flex items-center gap-1.5">
                                <span class="size-2.5 rounded-full bg-red-500/80 inline-block"></span>
                                <span class="size-2.5 rounded-full bg-yellow-500/80 inline-block"></span>
                                <span class="size-2.5 rounded-full bg-emerald-500/80 inline-block"></span>
                                <span class="text-[11px] font-mono text-neutral-400 ms-2">deploy@an-mastery:~$ update</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span id="terminal-status-badge" class="text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 animate-pulse">
                                    Running...
                                </span>
                                <button type="button" id="btn-close-terminal" title="{{ __('app_version.modal_btn_close') }}"
                                    class="text-neutral-400 hover:text-white p-0.5 rounded hover:bg-neutral-800 transition-colors cursor-pointer">
                                    <i data-lucide="x" class="size-3.5"></i>
                                </button>
                            </div>
                        </div>
                        <pre id="modal-terminal-output"
                            class="p-3.5 bg-neutral-950 text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto overflow-y-auto max-h-56 rounded-b-xl border border-neutral-800 whitespace-pre-wrap select-text m-0"></pre>
                    </div>
                </div>

                <!-- Manual Update Guide Accordion / Option -->
                <details class="group rounded-xl border border-(--color-gray)/15 bg-(--color-light-gray)/40 dark:bg-(--color-dark-slate)/40 overflow-hidden">
                    <summary class="flex items-center justify-between p-3.5 cursor-pointer select-none text-xs font-semibold text-(--color-dark) dark:text-(--color-light)">
                        <span class="flex items-center gap-2">
                            <i data-lucide="terminal" class="size-3.5 text-(--color-primary)"></i>
                            {{ __('app_version.manual_update_steps') }}
                        </span>
                        <i data-lucide="chevron-down" class="size-3.5 text-(--color-dark-gray) group-open:rotate-180 transition-transform"></i>
                    </summary>
                    <div class="p-3.5 pt-0 space-y-2">
                        <div class="flex justify-end">
                            <button type="button" id="btn-copy-update-cmd"
                                class="inline-flex items-center gap-1 text-[11px] font-medium text-(--color-primary) hover:underline cursor-pointer">
                                <i data-lucide="copy" class="size-3"></i>
                                <span>{{ __('app_version.modal_btn_copy_cmd') }}</span>
                            </button>
                        </div>
                        <pre id="modal-update-guide-cmd" class="p-3 rounded-xl bg-(--color-dark) text-emerald-400 font-mono text-xs leading-relaxed overflow-x-auto m-0">git pull origin develop
composer install
npm run build
php artisan optimize:clear</pre>
                    </div>
                </details>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between py-3 px-5 border-t border-(--color-gray)/15 bg-(--color-light-gray)/40 dark:bg-(--color-dark-slate)/40">
                <span class="text-[11px] text-(--color-dark-gray) dark:text-(--color-gray)">
                    {{ __('app_version.modal_footer_note') }}
                </span>
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="py-2 px-3.5 inline-flex items-center gap-x-2 text-xs font-medium rounded-xl border border-(--color-gray)/30 bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20 cursor-pointer"
                        data-hs-overlay="#modal-app-update">
                        {{ __('app_version.modal_btn_close') }}
                    </button>
                    <button type="button" id="btn-reload-page" onclick="window.location.reload()"
                        class="py-2 px-4 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/90 shadow-sm cursor-pointer">
                        <i data-lucide="rotate-cw" class="size-3.5"></i>
                        {{ __('app_version.modal_btn_reload') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
