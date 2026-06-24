@php
    $detailsJson = ($sablon->sablonDetails ?? collect())
        ->map(
            fn($d) => [
                'fabric_detail_id' => $d->fabric_detail_id,
                'color_fabric_id' => $d->color_fabric_id,
                'long_fabric' => $d->long_fabric,
            ],
        )
        ->values();

    $employeeDetailsJson = ($sablon->sablonEmployeeDetails ?? collect())
        ->map(
            fn($d) => [
                'fabric_detail_id' => $d->fabric_detail_id,
                'employee_id' => $d->employee_id,
                'layers' => $d->layers,
                'fee' => $d->fee,
                'additional_fee' => $d->additional_fee,
                'total' => $d->total,
                'is_change' => $d->is_change,
                'employee_change_id' => $d->employee_change_id,
                'is_payed' => $d->is_payed,
                'notes' => $d->notes,
            ],
        )
        ->values();
@endphp

<div x-data="sablonForm(
    {{ Js::from($detailsJson) }},
    {{ Js::from($employeeDetailsJson) }},
    {{ Js::from($fabricDetails) }},
    {{ Js::from($employees) }},
    {{ Js::from($priceEmployeesRaw) }},
    {{ Js::from($typeColorsRaw) }},
    {{ Js::from($sablon?->fabric_id ?? null) }},
    {{ Js::from($sablon?->supplier_id ?? null) }},
    {{ Js::from($fabrics) }}
)" x-init="init()">

    {{-- ===== CARD 1: Info Utama ===== --}}
    <div
        class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5 mb-6">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray) mb-4">Info Utama</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="mb-2 space-y-2">
                <x-select id="supplier_id" name="supplier_id" label="Supplier" :options="$suppliers" :value="$sablon?->supplier_id ?? null"
                    placeholder="Choose Supplier" search-placeholder="Search supplier..." clearable="true"
                    x-on:change="onSupplierChange($event)" />
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="fabric_id" name="fabric_id" label="Fabric" :options="$fabrics" :value="$sablon?->fabric_id ?? null"
                    placeholder="Choose Fabric" search-placeholder="Search fabric..." clearable="true"
                    x-on:change="onFabricChange($event)" />
                <small x-show="!selectedSupplierId" class="text-xs text-(--color-dark-gray)">
                    Pilih Supplier dahulu untuk memunculkan Fabric
                </small>
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="image_fabric_id" name="image_fabric_id" label="Image Fabric" :options="$imageFabrics"
                    :value="$sablon?->image_fabric_id ?? null" placeholder="Choose Image Fabric" search-placeholder="Search image fabric..."
                    clearable="true" />
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="type_color_id" name="type_color_id" label="Type Color" :options="$typeColors" :value="$sablon?->type_color_id ?? null"
                    placeholder="Choose Type Color" search-placeholder="Search type color..." clearable="true"
                    x-on:change="onTypeColorChange($event)" />
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="type_fabric_id" name="type_fabric_id" label="Type Fabric" :options="$typeFabrics"
                    :value="$sablon?->type_fabric_id ?? null" placeholder="Choose Type Fabric" search-placeholder="Search type fabric..."
                    clearable="true" />
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="price_employee_id" name="price_employee_id" label="Price Employee" :options="$priceEmployees"
                    :value="$sablon?->price_employee_id ?? null" placeholder="Choose Price Employee" search-placeholder="Search..."
                    clearable="true" x-on:change="onPriceEmployeeChange($event)" />
            </div>

            <div class="mb-2 space-y-2">
                <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Total Long Fabric
                </label>
                {{-- poin #3: tidak readonly/disabled, hanya open --}}
                <input type="text" :value="totalLongFabric" readonly
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) font-semibold focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                <input type="hidden" name="total_long_fabric" :value="totalLongFabric">
                <small class="text-xs text-(--color-dark-gray)">Otomatis dari total Fabric Detail</small>
            </div>

            <div class="mb-2 space-y-2">
                <label for="total_sablon"
                    class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Total Sablon
                </label>
                <input type="text" :value="formatNumber(computedTotalSablon)" readonly
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) font-semibold focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                <input type="hidden" name="total_sablon" :value="computedTotalSablon">
            </div>

            <div class="mb-2 space-y-2">
                <label for="date_sablon"
                    class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Date Sablon
                </label>
                <input type="date" id="date_sablon" name="date_sablon"
                    value="{{ old('date_sablon', $sablon?->date_sablon?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>

            <div class="mb-2 space-y-2">
                <x-select id="status" name="status" label="Status" :options="[
                    'ON_PROGRESS' => 'On Progress',
                    'DONE' => 'Done',
                    'DELIVERED' => 'Delivered',
                    'RETURNED' => 'Returned',
                ]" :value="$sablon?->status->value ?? 'ON_PROGRESS'"
                    placeholder="Choose Status" />
            </div>

            <div class="md:col-span-3 mb-2 space-y-2">
                <label for="notes"
                    class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">Notes</label>
                <textarea id="notes" name="notes" rows="2" maxlength="255"
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $sablon->notes ?? '' }}</textarea>
            </div>
        </div>
    </div>

    {{-- ===== CARD 2: Fabric Detail ===== --}}
    <div
        class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray)">
                Fabric Detail (Warna & Panjang Kain)
            </h3>
            <button type="button" @click="addFabricRow()"
                class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i> Add Detail
            </button>
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
                                        <span
                                            x-text="fabricDetailLabel(row.fabric_detail_id) || 'Choose Fabric Detail'"
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
        </div>
    </div>

    {{-- ===== CARD 3: Employee Detail (versi CARD, bukan tabel) ===== --}}
    <div
        class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray)">
                Employee Detail (Pekerja & Fee)
            </h3>
            <button type="button" @click="addEmployeeRow()"
                class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i> Add Employee
            </button>
        </div>

        <div class="space-y-4">
            <template x-for="(row, index) in employeeRows" :key="row.uid">
                <div class="rounded-lg border border-(--color-gray) dark:border-(--color-slate) p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light)"
                            x-text="'Employee #' + (index + 1)"></span>
                        <button type="button" @click="removeEmployeeRow(index)"
                            class="text-(--color-danger) hover:opacity-80 cursor-pointer">
                            <i data-lucide="trash-2" class="size-4"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        {{-- FIX #2: Employee ID dengan Clear Select --}}
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-(--color-dark-gray)">Employee</label>
                            <div class="relative" @click.outside="row.openEmp = false">
                                <button type="button" @click="row.openEmp = !row.openEmp"
                                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                       bg-(--color-light-gray) border border-(--color-gray)
                                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer">
                                    <span x-text="employeeLabel(row.employee_id) || 'Choose Employee'"
                                        :class="!row.employee_id && 'text-(--color-dark-gray)'"></span>

                                    <div class="flex items-center gap-2">
                                        {{-- Tombol Clear --}}
                                        <span x-show="row.employee_id" @click.stop="row.employee_id = ''"
                                            class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                            <i data-lucide="x" class="size-4"></i>
                                        </span>
                                        <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                    </div>
                                </button>
                                <div x-show="row.openEmp" x-cloak
                                    class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                       shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate) max-h-48 overflow-y-auto">
                                    <ul class="py-1">
                                        <template x-for="emp in employeeOptions" :key="emp.id">
                                            <li @click="row.employee_id = emp.id; row.openEmp = false"
                                                class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                                   hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                                x-text="emp.name"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-(--color-dark-gray)">Layers</label>
                            <input type="number" min="0" x-model.number="row.layers"
                                class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                    focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                    text-(--color-dark) dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-(--color-dark-gray)">Fee (otomatis)</label>
                            <input type="text" :value="formatNumber(row.fee)" readonly
                                class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                    focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                    text-(--color-dark) dark:text-(--color-light) font-semibold dark:bg-(--color-dark-slate) dark:border-(--color-slate)">
                        </div>

                        {{-- FIX #3: Additional Fee Multiple (Add/Remove) --}}
                        <div class="space-y-2 md:col-span-3">
                            <div class="flex items-center justify-between">
                                <label class="text-xs font-medium text-(--color-dark-gray)">Additional Fees</label>
                                <button type="button" @click="addAdditionalFee(row)"
                                    class="flex items-center gap-1 text-xs font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
                                    <i data-lucide="plus" class="size-3"></i> Add Fee
                                </button>
                            </div>

                            <template x-for="(fee, feeIndex) in row.additional_fee" :key="fee.uid">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 space-y-1">
                                        <input type="text" :value="formatSignedNumber(fee.nominal)"
                                            @input="fee.nominal = parseSignedNumber($event.target.value)"
                                            placeholder="contoh: 10000 atau -10000"
                                            class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                                focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                                text-(--color-dark) dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                    </div>
                                    <div class="flex-2 space-y-1">
                                        <input type="text" x-model="fee.notes" placeholder="Notes"
                                            class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                                focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                                text-(--color-dark) dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                    </div>
                                    <button type="button" @click="removeAdditionalFee(row, feeIndex)"
                                        class="text-(--color-danger) hover:opacity-80 cursor-pointer shrink-0">
                                        <i data-lucide="trash-2" class="size-4"></i>
                                    </button>
                                </div>
                            </template>

                            <template x-if="!row.additional_fee || row.additional_fee.length === 0">
                                <div class="text-xs text-(--color-dark-gray) italic">Belum ada additional fee</div>
                            </template>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-(--color-dark-gray)">Total Fee</label>
                            <input type="text" :value="formatNumber(rowTotal(row))" readonly
                                class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                    focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                    text-(--color-dark) dark:text-(--color-light) font-bold dark:bg-(--color-dark-slate) dark:border-(--color-slate)">
                        </div>

                        <div class="flex items-center gap-2 pt-5">
                            <input type="checkbox" x-model="row.is_change" class="checkbox-custom"
                                :id="'change_' + row.uid">
                            <label :for="'change_' + row.uid"
                                class="text-sm text-(--color-dark) dark:text-(--color-light)">Ganti Pekerja?</label>
                        </div>

                        {{-- FIX #2: Employee Change dengan Clear Select --}}
                        <div class="space-y-1 md:col-span-1" x-show="row.is_change">
                            <label class="text-xs font-medium text-(--color-dark-gray)">Employee Change</label>
                            <div class="relative" @click.outside="row.openEmpChange = false">
                                <button type="button" @click="row.openEmpChange = !row.openEmpChange"
                                    class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                       bg-(--color-light-gray) border border-(--color-gray)
                                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer">
                                    <span x-text="employeeLabel(row.employee_change_id) || 'Choose Employee'"
                                        :class="!row.employee_change_id && 'text-(--color-dark-gray)'"></span>

                                    <div class="flex items-center gap-2">
                                        <span x-show="row.employee_change_id"
                                            @click.stop="row.employee_change_id = ''"
                                            class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                            <i data-lucide="x" class="size-4"></i>
                                        </span>
                                        <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                    </div>
                                </button>
                                <div x-show="row.openEmpChange" x-cloak
                                    class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                       shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate) max-h-48 overflow-y-auto">
                                    <ul class="py-1">
                                        <template x-for="emp in employeeOptions" :key="emp.id">
                                            <li @click="row.employee_change_id = emp.id; row.openEmpChange = false"
                                                class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                                   hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                                x-text="emp.name"></li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-5">
                            <input type="checkbox" x-model="row.is_payed" class="checkbox-custom"
                                :id="'payed_' + row.uid">
                            <label :for="'payed_' + row.uid"
                                class="text-sm text-(--color-dark) dark:text-(--color-light)">Sudah Dibayar</label>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="employeeRows.length === 0">
                <div class="text-center text-(--color-dark-gray) py-6">Belum ada employee detail</div>
            </template>
        </div>
    </div>
</div>
