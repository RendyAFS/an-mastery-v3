<div id="bulk-action-bar"
    class="hidden fixed inset-x-0 bottom-0 z-40 border-t border-(--color-gray)/20 bg-(--color-light) dark:bg-(--color-dark) shadow-lg">
    <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
        <span id="bulk-selected-count" class="text-sm font-medium"></span>

        <div class="flex items-center gap-2">
           <select id="bulk-status-select" class="hidden w-32"
                data-hs-select='{
                    "placeholder": "{{ __('sablon.bulk.select_status') }}",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "relative py-1.5 ps-3 pe-8 flex gap-x-2 w-full cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                    "dropdownClasses": "mt-2 z-50 w-32 max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-y-auto",
                    "optionClasses": "ps-3 py-2 px-4 w-auto text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-auto\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 inset-e-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4\"></i></div>"
                }'>
                <option value=""></option>
                @foreach (\App\Enums\StatusSablonEnum::cases() as $status)
                    <option value="{{ $status->value }}">{{ __('sablon.statuses.' . $status->value) }}</option>
                @endforeach
            </select>

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
