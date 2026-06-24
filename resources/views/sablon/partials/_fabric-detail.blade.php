<div
    class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray)">
            Fabric Detail (Color - Stock)
        </h3>
    </div>

    <div class="relative overflow-visible rounded-lg border border-(--color-gray) dark:border-(--color-slate)">
        <table class="w-full text-sm">
            <thead>
                <tr
                    class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) text-(--color-dark) dark:text-(--color-light)">
                    <th class="px-4 py-3 text-left font-semibold" style="width: 5%">No</th>
                    <th class="px-4 py-3 text-left font-semibold" style="width: 45%">Fabric Detail (Color - Stock)
                    </th>
                    <th class="px-4 py-3 text-left font-semibold" style="width: 35%">Long Fabric</th>
                    <th class="px-4 py-3 text-center font-semibold" style="width: 15%">Action</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, index) in fabricRows" :key="row.uid">
                    <tr class="border-t border-(--color-gray) dark:border-(--color-slate)">
                        <td class="px-4 py-3 text-center" x-text="index + 1 + '.'"></td>

                        <td class="px-4 py-3">
                            <div class="relative" @click.outside="row.open = false">
                                <button type="button" @click="row.open = !row.open"
                                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                       bg-(--color-light-gray) border border-(--color-gray)
                                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer">
                                    <span x-text="fabricDetailLabel(row.fabric_detail_id) || 'Choose Fabric Detail'"
                                        :class="!row.fabric_detail_id && 'text-(--color-dark-gray)'"></span>
                                    <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                </button>

                                <div x-show="row.open" x-cloak
                                    class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                       shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                    <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                        <input type="text" x-model="row.search" placeholder="Search color..."
                                            class="w-full px-3 py-1.5 text-sm rounded-md bg-(--color-light-gray) border border-(--color-gray)
                                               text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                               dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                    </div>
                                    <ul class="max-h-48 overflow-y-auto py-1">
                                        <template x-for="option in filteredFabricDetails(row.search)"
                                            :key="option.id">
                                            <li @click="selectFabricDetail(row, option)"
                                                class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                                   hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                                x-text="option.color_name + ' (stock: ' + option.stock + ')'"></li>
                                        </template>
                                        <template x-if="filteredFabricDetails(row.search).length === 0">
                                            <li class="px-4 py-2 text-sm text-(--color-dark-gray)">No fabric detail
                                                found for selected Fabric</li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3">
                            <input type="number" min="0" step="1" x-model="row.long_fabric"
                                class="px-3 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                        </td>

                        <td class="px-4 py-3 text-center">
                            <button type="button" x-show="fabricRows.length > 1" @click="removeFabricRow(index)"
                                class="text-(--color-danger) hover:opacity-80 cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
        <div class="p-4 flex justify-end">
            <button type="button" @click="addFabricRow()"
                class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i> Add Detail
            </button>
        </div>
    </div>
</div>
