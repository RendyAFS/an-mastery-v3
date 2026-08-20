<div id="bulk-action-bar"
    class="hidden fixed inset-x-0 bottom-0 z-40 border-t border-(--color-gray)/20 bg-(--color-light) dark:bg-(--color-dark) shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
        <span id="bulk-selected-count" class="text-sm font-medium"></span>

        <div class="flex items-center gap-2">
            <x-select id="bulk-status-select" name="bulk-status-select" placeholder="{{ __('sablon.bulk.select_status') }}"
                :options="__('sablon.statuses')" dropdown-scope="window" :search="false" wrapperClass="w-36"
                bgClass="bg-(--color-light) dark:bg-(--color-dark-slate)" />

            <button type="button" id="btn-bulk-apply"
                class="px-4 py-2 rounded-lg bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                {{ __('sablon.bulk.apply') }}
            </button>

            <button type="button" id="btn-bulk-cancel"
                class="px-4 py-2 rounded-lg bg-(--color-gray)/20 hover:bg-(--color-gray)/30 cursor-pointer">
                {{ __('sablon.bulk.cancel') }}
            </button>
        </div>
    </div>
</div>

<style>
    #sablon-cardgrid .cg-select-checkbox {
        display: none;
    }

    #sablon-cardgrid.select-mode .cg-select-checkbox {
        display: flex;
    }

    #sablon-cardgrid.select-mode .cg-card-wrapper {
        cursor: pointer;
    }

    #sablon-cardgrid .cg-card-wrapper.selected {
        outline: 2px solid var(--color-primary);
        outline-offset: 2px;
        border-radius: 0.75rem;
    }

    body.bulk-bar-active #sidebar-floating-trigger {
        bottom: 5.5rem;
    }

    #sablon-cardgrid .cg-card-wrapper {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        user-select: none;
    }
</style>
