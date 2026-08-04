<div id="cover-style-modal" class="hidden fixed inset-0 z-9999 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" data-modal-close></div>

    <div
        class="relative z-9999 bg-(--color-light) dark:bg-(--color-dark) rounded-2xl shadow-xl w-full max-w-md p-5 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold">{{ __('bill-supplier.cover_style.title') }}</h3>
            <button type="button" data-modal-close class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>

        <div id="cover-style-preview" class="relative rounded-xl px-4 pt-4 pb-6 overflow-hidden"></div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label
                    class="text-xs font-medium text-(--color-dark)">{{ __('bill-supplier.cover_style.color_from') }}</label>
                <input type="color" id="cs-color-from"
                    class="w-full h-10 rounded-lg cursor-pointer border border-(--color-dark)/20">
            </div>
            <div>
                <label
                    class="text-xs font-medium text-(--color-dark)">{{ __('bill-supplier.cover_style.color_to') }}</label>
                <input type="color" id="cs-color-to"
                    class="w-full h-10 rounded-lg cursor-pointer border border-(--color-dark)/20">
            </div>
        </div>

        <div class="flex flex-wrap gap-2" id="cover-style-palette"></div>

        <x-select name="cs_icon" id="cs-icon" label="{{ __('bill-supplier.cover_style.icon_label') }}"
            placeholder="{{ __('bill-supplier.cover_style.icon_placeholder') }}"
            searchPlaceholder="{{ __('bill-supplier.cover_style.icon_search_placeholder') }}" :options="$iconOptions"
            :dropdownMaxH="'max-h-40'" />

        <x-select name="cs_pattern" id="cs-pattern" label="{{ __('bill-supplier.cover_style.pattern_label') }}"
            placeholder="{{ __('bill-supplier.cover_style.pattern_placeholder') }}"
            searchPlaceholder="{{ __('bill-supplier.cover_style.pattern_search_placeholder') }}" :options="$patternOptions"
            :dropdownMaxH="'max-h-40'" />

        <div class="flex items-center justify-end pt-2">
            <div class="flex gap-2">
                <button type="button" data-modal-close
                    class="px-4 py-2 rounded-lg text-sm border border-(--color-gray)/20 cursor-pointer">
                    {{ __('bill-supplier.cover_style.cancel') }}
                </button>
                <button type="button" id="cs-save"
                    class="px-4 py-2 rounded-lg text-sm bg-(--color-primary) text-white cursor-pointer">
                    {{ __('bill-supplier.cover_style.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
