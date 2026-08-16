<div
    class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5 mb-6">
    <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray) mb-4">
        {{ __('sablon.main_info.title') }}</h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="mb-2 space-y-2">
            <x-select id="supplier_id" name="supplier_id" label="{{ __('sablon.main_info.fields.supplier') }}"
                :options="$suppliers" :value="$sablon?->supplier_id ?? null" placeholder="{{ __('sablon.main_info.placeholders.supplier') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.supplier') }}" clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="fabric_id" name="fabric_id" label="{{ __('sablon.main_info.fields.fabric') }}"
                :options="$fabrics" :value="$sablon?->fabric_id ?? null" placeholder="{{ __('sablon.main_info.placeholders.fabric') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.fabric') }}" clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="image_fabric_id" name="image_fabric_id"
                label="{{ __('sablon.main_info.fields.image_fabric') }}" :options="$imageFabrics" :value="$sablon?->image_fabric_id ?? null"
                placeholder="{{ __('sablon.main_info.placeholders.image_fabric') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.image_fabric') }}" clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="type_color_id" name="type_color_id" label="{{ __('sablon.main_info.fields.type_color') }}"
                :options="$typeColors" :value="$sablon?->type_color_id ?? null" placeholder="{{ __('sablon.main_info.placeholders.type_color') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.type_color') }}" clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="type_fabric_id" name="type_fabric_id" label="{{ __('sablon.main_info.fields.type_fabric') }}"
                :options="$typeFabrics" :value="$sablon?->type_fabric_id ?? null" placeholder="{{ __('sablon.main_info.placeholders.type_fabric') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.type_fabric') }}" clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="price_employee_id" name="price_employee_id"
                label="{{ __('sablon.main_info.fields.price_employee') }}" :options="$priceEmployees" :value="$sablon?->price_employee_id ?? null"
                placeholder="{{ __('sablon.main_info.placeholders.price_employee') }}"
                search-placeholder="{{ __('sablon.main_info.search_placeholders.price_employee') }}"
                clearable="true" />
        </div>

        <div class="mb-2 space-y-2">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('sablon.main_info.fields.total_long_fabric') }}
            </label>
            <input type="text" :value="totalLongFabric" readonly
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) font-semibold focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            <input type="hidden" name="total_long_fabric" :value="totalLongFabric">
            <small class="text-xs text-(--color-dark-gray)">{{ __('sablon.main_info.hint_total_long_fabric') }}</small>
        </div>

        <div class="mb-2 space-y-2">
            <label for="total_sablon" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('sablon.main_info.fields.total_sablon') }}
            </label>
            <input type="text" :value="formatNumber(computedTotalSablon)" readonly
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) font-semibold focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            <input type="hidden" name="total_sablon" :value="computedTotalSablon">
            <small class="text-xs text-(--color-dark-gray)">{{ __('sablon.main_info.hint_total_long_fabric') }}</small>
        </div>

        <div class="mb-2 space-y-2">
            <x-datepicker id="date_sablon" name="date_sablon" label="{{ __('sablon.main_info.fields.date_sablon') }}"
                :value="old('date_sablon', $sablon?->date_sablon?->format('Y-m-d') ?? now()->format('Y-m-d'))" />
        </div>

        <div class="mb-2 space-y-2">
            <x-select id="status" name="status" label="{{ __('sablon.main_info.fields.status') }}"
                :options="$statusOptions" :value="$sablon?->status->value ?? 'ON_PROGRESS'" placeholder="{{ __('sablon.main_info.placeholders.status') }}" />
        </div>

        <div class="flex items-center gap-2 mb-2">
            <input type="checkbox" id="is_billed_in_advance" name="is_billed_in_advance" class="checkbox-custom"
                {{ $sablon?->is_billed_in_advance ? 'checked' : '' }}>
            <label for="is_billed_in_advance" class="text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('sablon.main_info.fields.is_billed_in_advance') }}
            </label>
        </div>

        <div class="md:col-span-3 mb-2 space-y-2">
            <label for="notes"
                class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">{{ __('sablon.main_info.fields.notes') }}</label>
            <textarea id="notes" name="notes" rows="2" maxlength="255"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $sablon->notes ?? '' }}</textarea>
        </div>
    </div>
</div>
