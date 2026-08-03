<div class="grid grid-cols-2 gap-3">
    <div class="coll-span-1">
        <x-select id="type_fabric_id" name="type_fabric_id" label="{{ __('price-employee.fields.type_fabric') }}"
            :options="$typeFabrics" :value="$priceEmployee->type_fabric_id ?? null" placeholder="{{ __('price-employee.placeholders.type_fabric') }}"
            search-placeholder="{{ __('price-employee.search_placeholders.type_fabric') }}" clearable="true" />
    </div>

    <div class="coll-span-1">
        <x-select id="type_color_id" name="type_color_id" label="{{ __('price-employee.fields.type_color') }}"
            :options="$typeColors" :value="$priceEmployee->type_color_id ?? null" placeholder="{{ __('price-employee.placeholders.type_color') }}"
            search-placeholder="{{ __('price-employee.search_placeholders.type_color') }}" clearable="true"
            dropdown-scope="window" />
    </div>

    <div class="col-span-full">
        <label for="price" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('price-employee.fields.price') }}
        </label>
        <input type="text" inputmode="numeric" id="price" name="price"
            value="{{ $priceEmployee->price ?? '' }}" data-rupiah required
            class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="col-span-full">
        <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('price-employee.fields.notes') }}
        </label>

        <textarea id="notes" name="notes" rows="3"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $priceEmployee->notes ?? '' }}</textarea>
    </div>
</div>
