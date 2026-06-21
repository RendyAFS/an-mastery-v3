<div class="grid grid-cols-2 gap-3">
    <div class="col-span-full">
        <x-select id="supplier_id" name="supplier_id" label="Supplier" :options="$suppliers" :value="$priceSupplier->supplier_id ?? null"
            placeholder="Choose Supplier" search-placeholder="Search supplier..." clearable="true" />
    </div>

    <div class="coll-span-1">
        <x-select id="type_fabric_id" name="type_fabric_id" label="Type Fabric" :options="$typeFabrics" :value="$priceSupplier->type_fabric_id ?? null"
            placeholder="Choose Type Fabric" search-placeholder="Search type fabric..." clearable="true" />
    </div>

    <div class="coll-span-1">
        <x-select id="type_color_id" name="type_color_id" label="Type Color" :options="$typeColors" :value="$priceSupplier->type_color_id ?? null"
            placeholder="Choose Type Color" search-placeholder="Search type color..." clearable="true"
            dropdown-scope="window" />
    </div>

    <div class="col-span-full">
        <label for="price" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            Price
        </label>
        <input type="number" id="price" name="price" value="{{ $priceSupplier->price ?? '' }}" required
            class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="col-span-full">
        <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            Notes
        </label>

        <textarea id="notes" name="notes" rows="3"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $priceSupplier->notes ?? '' }}</textarea>
    </div>
</div>
