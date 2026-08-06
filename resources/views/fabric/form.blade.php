<div x-data="fabricForm({{ ($fabric->fabricDetails ?? collect())->map(
        fn($d) => [
            'color_fabric_id' => $d->color_fabric_id,
            'stock' => $d->stock,
            'notes' => $d->notes,
        ],
    )->values()->toJson() }}, {{ Js::from($colorFabrics) }})" x-init="init()">

    {{-- Fabric Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="col-span-1">
            <div class="mb-2 space-y-2">
                <x-select id="supplier_id" name="supplier_id" label="{{ __('fabric.fields.supplier') }}" :options="$suppliers"
                    :value="$fabric->supplier_id ?? null" placeholder="{{ __('fabric.placeholders.supplier') }}"
                    search-placeholder="{{ __('fabric.search_placeholders.supplier') }}" clearable="true" />
            </div>
        </div>

        <div class="col-span-1">
            <div class="mb-2 space-y-2">
                <x-select id="type_fabric_id" name="type_fabric_id" label="{{ __('fabric.fields.type_fabric') }}"
                    :options="$typeFabrics" :value="$fabric->type_fabric_id ?? null" placeholder="{{ __('fabric.placeholders.type_fabric') }}"
                    search-placeholder="{{ __('fabric.search_placeholders.type_fabric') }}" clearable="true" />
            </div>
        </div>

        <div class="col-span-1">
            <div class="mb-2 space-y-2">
                <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('fabric.fields.total_stock') }}
                </label>

                <input type="text" :value="totalStock" disabled readonly
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark-gray) font-semibold cursor-not-allowed
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-dark-gray)">

                <small class="text-xs text-(--color-dark-gray)">
                    {{ __('fabric.hints.total_stock') }}
                </small>
            </div>
        </div>

        <div class="col-span-1">
            <div class="mb-2 space-y-2">
                <label for="seri" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('fabric.fields.type_seri') }}
                </label>

                <input type="number" id="seri" name="seri" min="1" value="{{ $fabric->seri ?? 4 }}"
                    required
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>
        </div>

        @isset($fabric)
            <div class="col-span-1">
                <div class="mb-2 space-y-2">
                    <label for="code" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('fabric.fields.code') }}
                    </label>

                    <input type="text" id="code" value="{{ $fabric->code }}" disabled readonly
                        class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                           text-(--color-dark-gray) cursor-not-allowed
                           dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-dark-gray)">

                    <small class="text-xs text-(--color-dark-gray)">
                        {{ __('fabric.hints.code') }}
                    </small>
                </div>
            </div>
        @endisset

        <div class="mb-2 space-y-2">
            <label for="date_coming" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('fabric.fields.date_coming') }}
            </label>
            <input type="date" id="date_coming" name="date_coming"
                value="{{ old('date_coming', $fabric?->date_coming?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="col-span-1 md:col-span-2">
            <div class="mb-2 space-y-2">
                <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('fabric.fields.notes') }}
                </label>

                <textarea id="notes" name="notes" rows="3" maxlength="255"
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $fabric->notes ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- Fabric Detail Table --}}
    <div class="mt-2">
        <div class="flex items-center justify-between mb-3">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('fabric.detail.title') }}
            </label>

            <button type="button" @click="addRow()"
                class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('fabric.detail.add') }}
            </button>
        </div>

        <div class="relative overflow-visible rounded-lg border border-(--color-gray) dark:border-(--color-slate)">
            <table class="w-full text-sm">
                <thead>
                    <tr
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) text-(--color-dark) dark:text-(--color-light)">
                        <th class="px-4 py-3 text-left font-semibold" style="width: 5%">{{ __('fabric.detail.no') }}
                        </th>
                        <th class="px-4 py-3 text-left font-semibold" style="width: 35%">
                            {{ __('fabric.detail.color') }}</th>
                        <th class="px-4 py-3 text-left font-semibold" style="width: 25%">
                            {{ __('fabric.detail.stock') }}</th>
                        <th class="px-4 py-3 text-left font-semibold" style="width: 25%">
                            {{ __('fabric.detail.notes') }}</th>
                        <th class="px-4 py-3 text-center font-semibold" style="width: 10%">
                            {{ __('fabric.detail.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(row, index) in rows" :key="row.uid">
                        <tr class="border-t border-(--color-gray) dark:border-(--color-slate)">
                            <td class="px-4 py-3 text-center text-(--color-dark) dark:text-(--color-light)"
                                x-text="index + 1 + '.'"></td>

                            {{-- Color select, styled like x-select supplier --}}
                            <td class="px-4 py-3">
                                <div class="relative" @click.outside="row.open = false">
                                    <button type="button" @click="row.open = !row.open"
                                        class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                           bg-(--color-light-gray) border border-(--color-gray)
                                           text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                           dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer">
                                        <span
                                            x-text="colorName(row.color_fabric_id) || '{{ __('fabric.placeholders.color') }}'"
                                            :class="!row.color_fabric_id && 'text-(--color-dark-gray)'"></span>
                                        <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                    </button>

                                    <div x-show="row.open" x-cloak
                                        class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                           shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                        <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                            <input type="text" x-model="row.search"
                                                placeholder="{{ __('fabric.search_placeholders.color') }}"
                                                class="w-full px-3 py-1.5 text-sm rounded-md bg-(--color-light-gray) border border-(--color-gray)
                                                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                        </div>

                                        <ul class="max-h-48 overflow-y-auto py-1">
                                            <template x-if="row.color_fabric_id">
                                                <li @click="row.color_fabric_id = ''; row.open = false"
                                                    class="px-4 py-2 text-sm text-(--color-danger) hover:bg-(--color-light-gray)
                                                       dark:hover:bg-(--color-dark-slate) cursor-pointer">
                                                    {{ __('fabric.clear_selection') }}
                                                </li>
                                            </template>

                                            <template x-for="option in filteredColors(row.search)"
                                                :key="option.id">
                                                <li @click="row.color_fabric_id = option.id; row.open = false; row.search = ''"
                                                    class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                                       hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                                    x-text="option.name"></li>
                                            </template>

                                            <template x-if="filteredColors(row.search).length === 0">
                                                <li class="px-4 py-2 text-sm text-(--color-dark-gray)">
                                                    {{ __('fabric.no_color_found') }}
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <input type="number" min="0" x-model="row.stock"
                                    class="px-3 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                            </td>

                            <td class="px-4 py-3">
                                <input type="text" maxlength="255" x-model="row.notes"
                                    class="px-3 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                            </td>

                            <td class="px-4 py-3 text-center">
                                <button type="button" x-show="rows.length > 1" @click="removeRow(index)"
                                    class="text-(--color-danger) hover:opacity-80 cursor-pointer">
                                    <i data-lucide="trash-2" class="size-4"></i>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>
