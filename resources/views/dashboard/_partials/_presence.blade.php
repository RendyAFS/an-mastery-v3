<div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col h-170">
    <div class="mb-3 shrink-0">
        <p class="text-sm font-semibold">{{ __('dashboard.presence.title') }}</p>
        <p id="presence-week-range" class="text-xs text-(--color-dark-gray)"></p>
    </div>
    <div id="presence-list" class="flex-1 min-h-0 space-y-2 overflow-y-auto custom-scrollbar"></div>
</div>
