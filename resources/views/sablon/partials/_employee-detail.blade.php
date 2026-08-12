<div
    class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray)">
            {{ __('sablon.employee_detail.title') }}
        </h3>
    </div>

    <div class="space-y-4">
        <template x-for="(row, index) in employeeRows" :key="row.uid">
            <div class="rounded-lg border p-4"
                :class="row.locked ?
                    'border-(--color-gray)/60 dark:border-(--color-slate)/60 bg-(--color-light-gray)/30 dark:bg-(--color-dark-slate)/30' :
                    'border-(--color-gray) dark:border-(--color-slate)'">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="flex items-center gap-2 text-sm font-semibold text-(--color-dark) dark:text-(--color-light)">
                        <span x-text="'{{ __('sablon.employee_detail.employee') }} #' + (index + 1)"></span>
                        <span x-show="row.locked" x-cloak class="text-xs px-2 py-0.5 rounded-full"
                            :class="row.isSettlementRow ? 'bg-(--color-primary)/10 text-(--color-primary)' :
                                'bg-(--color-warning)/10 text-(--color-warning)'"
                            x-text="row.isSettlementRow ? '{{ __('sablon.employee_detail.settlement_badge') }}' : '{{ __('sablon.employee_detail.locked_badge') }}'"></span>
                    </span>
                    <button type="button" @click="removeEmployeeRow(index)" x-show="!row.locked"
                        class="text-(--color-danger) hover:opacity-80 cursor-pointer">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="space-y-1">
                        <label
                            class="text-xs font-medium text-(--color-dark-gray)">{{ __('sablon.employee_detail.employee') }}</label>
                        <div class="relative" @click.outside="row.openEmp = false">
                            <button type="button" @click="row.openEmp = !row.openEmp" :disabled="row.locked"
                                class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                bg-(--color-light-gray) border border-(--color-gray)
                                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer
                                disabled:opacity-60 disabled:cursor-not-allowed">
                                <span
                                    x-text="employeeLabel(row.employee_id) || '{{ __('sablon.employee_detail.choose_employee') }}'"
                                    :class="!row.employee_id && 'text-(--color-dark-gray)'"></span>

                                <div class="flex items-center gap-2">
                                    <span x-show="row.employee_id && !row.locked" @click.stop="row.employee_id = ''"
                                        class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                        <i data-lucide="x" class="size-4"></i>
                                    </span>
                                    <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                </div>
                            </button>
                            <div x-show="row.openEmp && !row.locked" x-cloak
                                class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                    <input type="text" x-model="row.searchEmp"
                                        placeholder="{{ __('sablon.employee_detail.search_employee_placeholder') }}"
                                        class="w-full px-3 py-1.5 text-sm rounded-md bg-(--color-light-gray) border border-(--color-gray)
                                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                </div>
                                <ul class="max-h-48 overflow-y-auto py-1">
                                    <template x-for="emp in filteredEmployees(row.searchEmp)" :key="emp.id">
                                        <li @click="row.employee_id = emp.id; row.openEmp = false; row.searchEmp = ''"
                                            class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                            hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                            x-text="emp.name"></li>
                                    </template>
                                    <template x-if="filteredEmployees(row.searchEmp).length === 0">
                                        <li class="px-4 py-2 text-sm text-(--color-dark-gray)">
                                            {{ __('sablon.employee_detail.no_employee_found') }}</li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-(--color-dark-gray)">
                            {{ __('sablon.employee_detail.layers') }}
                        </label>

                        <x-input-number model="row.layers" :min="1" x-bind:disabled="row.locked" />
                    </div>

                    <div class="space-y-1">
                        <label
                            class="text-xs font-medium text-(--color-dark-gray)">{{ __('sablon.employee_detail.fee_auto') }}</label>
                        <input type="text" :value="formatNumber(computeFee(row))" readonly
                            class="px-3 py-2 w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                text-(--color-dark) dark:text-(--color-light) font-semibold dark:bg-(--color-dark-slate) dark:border-(--color-slate)">
                    </div>

                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" x-model="row.is_change" :disabled="row.locked" class="checkbox-custom"
                            :id="'change_' + row.uid">
                        <label :for="'change_' + row.uid"
                            class="text-sm text-(--color-dark) dark:text-(--color-light)">{{ __('sablon.employee_detail.change_employee') }}</label>
                    </div>

                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" x-model="row.is_bon" :disabled="row.locked" class="checkbox-custom"
                            :id="'bon_' + row.uid">
                        <label :for="'bon_' + row.uid"
                            class="text-sm text-(--color-dark) dark:text-(--color-light)">{{ __('sablon.employee_detail.is_bon') }}</label>
                    </div>

                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" x-model="row.is_paid" :disabled="row.locked" class="checkbox-custom"
                            :id="'payed_' + row.uid">
                        <label :for="'payed_' + row.uid"
                            class="text-sm text-(--color-dark) dark:text-(--color-light)">{{ __('sablon.employee_detail.is_paid') }}</label>
                    </div>

                    <div class="space-y-1 md:col-span-1" x-show="row.is_change">
                        <label
                            class="text-xs font-medium text-(--color-dark-gray)">{{ __('sablon.employee_detail.employee_change') }}</label>
                        <div class="relative" @click.outside="row.openEmpChange = false">
                            <button type="button" @click="row.openEmpChange = !row.openEmpChange"
                                :disabled="row.locked"
                                class="flex items-center justify-between w-full px-4 py-2 rounded-lg
                                bg-(--color-light-gray) border border-(--color-gray)
                                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light) cursor-pointer
                                disabled:opacity-60 disabled:cursor-not-allowed">
                                <span
                                    x-text="employeeLabel(row.employee_change_id) || '{{ __('sablon.employee_detail.choose_employee') }}'"
                                    :class="!row.employee_change_id && 'text-(--color-dark-gray)'"></span>

                                <div class="flex items-center gap-2">
                                    <span x-show="row.employee_change_id && !row.locked"
                                        @click.stop="row.employee_change_id = ''"
                                        class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                        <i data-lucide="x" class="size-4"></i>
                                    </span>
                                    <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                </div>
                            </button>
                            <div x-show="row.openEmpChange && !row.locked" x-cloak
                                class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                    <input type="text" x-model="row.searchEmpChange"
                                        placeholder="{{ __('sablon.employee_detail.search_employee_placeholder') }}"
                                        class="w-full px-3 py-1.5 text-sm rounded-md bg-(--color-light-gray) border border-(--color-gray)
                                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                                </div>
                                <ul class="max-h-48 overflow-y-auto py-1">
                                    <template x-for="emp in filteredEmployees(row.searchEmpChange)"
                                        :key="emp.id">
                                        <li @click="row.employee_change_id = emp.id; row.openEmpChange = false; row.searchEmpChange = ''"
                                            class="px-4 py-2 text-sm text-(--color-dark) dark:text-(--color-light)
                                            hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer"
                                            x-text="emp.name"></li>
                                    </template>
                                    <template x-if="filteredEmployees(row.searchEmpChange).length === 0">
                                        <li class="px-4 py-2 text-sm text-(--color-dark-gray)">
                                            {{ __('sablon.employee_detail.no_employee_found') }}</li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-3 space-y-3 pt-3 border-t border-(--color-gray) dark:border-(--color-slate)"
                        x-show="row.is_bon" x-cloak>
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-medium text-(--color-dark-gray)">
                                {{ __('sablon.employee_detail.additional_fee_label') }}
                            </label>
                            <button type="button" @click="addAdditionalFeeRow(row)" :disabled="row.locked"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                                <i data-lucide="plus" class="size-3.5"></i>
                                {{ __('sablon.employee_detail.add_fee') }}
                            </button>
                        </div>

                        <template x-for="(af, afIndex) in row.additionalFees" :key="af.uid">
                            <div
                                class="relative grid grid-cols-2 gap-3 p-3 rounded-lg border border-(--color-gray)/40 dark:border-(--color-dark-gray)">
                                <button type="button" @click="removeAdditionalFeeRow(row, afIndex)"
                                    x-show="!row.locked"
                                    class="absolute top-2 inset-e-2 p-1 rounded-lg hover:bg-(--color-gray)/20 text-(--color-danger) cursor-pointer">
                                    <i data-lucide="trash-2" class="size-3.5"></i>
                                </button>

                                <div class="col-span-1">
                                    <label class="text-xs font-medium text-(--color-dark-gray)">
                                        {{ __('sablon.employee_detail.nominal') }}
                                    </label>
                                    <input type="text" inputmode="numeric" x-model="af.nominalDisplay"
                                        :disabled="row.locked" @input="onAdditionalFeeNominalInput(af, $event)"
                                        class="mt-1 px-3 py-2 block w-full rounded-lg
                                            bg-(--color-light-gray) border border-(--color-gray)
                                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                                            disabled:opacity-60 disabled:cursor-not-allowed">
                                </div>

                                <div class="col-span-1">
                                    <label class="text-xs font-medium text-(--color-dark-gray)">
                                        {{ __('sablon.employee_detail.notes') }}
                                    </label>
                                    <textarea x-model="af.notes" rows="3" maxlength="255" :disabled="row.locked"
                                        placeholder="{{ __('sablon.employee_detail.notes_placeholder') }}"
                                        class="mt-1 px-3 py-2 block w-full rounded-lg
                                            bg-(--color-light-gray) border border-(--color-gray)
                                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                                            disabled:opacity-60 disabled:cursor-not-allowed"></textarea>
                                </div>
                            </div>
                        </template>

                        <template x-if="row.additionalFees.length === 0">
                            <p class="text-xs text-(--color-dark-gray)">
                                {{ __('sablon.employee_detail.no_additional_fee_yet') }}</p>
                        </template>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="employeeRows.length === 0">
            <div class="text-center text-(--color-dark-gray) py-6">
                {{ __('sablon.employee_detail.no_employee_detail_yet') }}</div>
        </template>
    </div>
    <div class="p-4 flex justify-end">
        <button type="button" @click="addEmployeeRow()"
            class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
            <i data-lucide="plus" class="size-4"></i> {{ __('sablon.employee_detail.add_employee') }}
        </button>
    </div>
</div>
