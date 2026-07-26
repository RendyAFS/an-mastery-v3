<div
    class="bg-(--color-light) dark:bg-(--color-dark-slate)/40 border border-(--color-gray) dark:border-(--color-slate) rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold uppercase tracking-wide text-(--color-dark-gray)">
            Employee Detail (Employee & Fee)
        </h3>
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
                                    <span x-show="row.employee_id" @click.stop="row.employee_id = ''"
                                        class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                        <i data-lucide="x" class="size-4"></i>
                                    </span>
                                    <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                </div>
                            </button>
                            <div x-show="row.openEmp" x-cloak
                                class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                    <input type="text" x-model="row.searchEmp" placeholder="Search employee..."
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
                                        <li class="px-4 py-2 text-sm text-(--color-dark-gray)">No employee found</li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-(--color-dark-gray)">
                            Layers
                        </label>

                        <x-input-number model="row.layers" :min="1" />
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
                            <div class="text-xs text-(--color-dark-gray) italic">Not yet added additional fee</div>
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
                            class="text-sm text-(--color-dark) dark:text-(--color-light)">Change Employee?</label>
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
                                    <span x-show="row.employee_change_id" @click.stop="row.employee_change_id = ''"
                                        class="text-(--color-dark-gray) hover:text-(--color-danger) cursor-pointer transition">
                                        <i data-lucide="x" class="size-4"></i>
                                    </span>
                                    <i data-lucide="chevron-down" class="size-4 text-(--color-dark-gray)"></i>
                                </div>
                            </button>
                            <div x-show="row.openEmpChange" x-cloak
                                class="absolute z-10 mt-1 w-full rounded-lg bg-(--color-light) border border-(--color-gray)
                                shadow-lg dark:bg-(--color-dark) dark:border-(--color-slate)">
                                <div class="p-2 border-b border-(--color-gray) dark:border-(--color-slate)">
                                    <input type="text" x-model="row.searchEmpChange"
                                        placeholder="Search employee..."
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
                                        <li class="px-4 py-2 text-sm text-(--color-dark-gray)">No employee found</li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" x-model="row.is_payed" class="checkbox-custom"
                            :id="'payed_' + row.uid">
                        <label :for="'payed_' + row.uid"
                            class="text-sm text-(--color-dark) dark:text-(--color-light)">Is Paid</label>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="employeeRows.length === 0">
            <div class="text-center text-(--color-dark-gray) py-6">Belum ada employee detail</div>
        </template>
    </div>
    <div class="p-4 flex justify-end">
        <button type="button" @click="addEmployeeRow()"
            class="flex items-center gap-1 text-sm font-semibold text-(--color-primary) hover:opacity-80 cursor-pointer">
            <i data-lucide="plus" class="size-4"></i> Add Employee
        </button>
    </div>
</div>
