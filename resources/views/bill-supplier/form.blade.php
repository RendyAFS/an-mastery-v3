@php $isEdit = isset($billSuppliers) && $billSuppliers !== null; @endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    <div class="lg:col-span-7 space-y-2">
        <div class="flex items-center justify-between gap-2">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ $isEdit ? __('bill-supplier.form.sablon_label_edit') : __('bill-supplier.form.sablon_label_create') }}
            </label>
            <span id="sablon-selected-summary" class="text-xs text-(--color-gray)">
                {{ $isEdit ? __('bill-supplier.form.selected_summary', ['count' => count($billSuppliers)]) : __('bill-supplier.form.no_selected') }}
            </span>
        </div>

        <div class="mb-2">
            <input type="text" id="sablon-search" placeholder="{{ __('bill-supplier.form.search_placeholder') }}"
                class="w-full px-4 py-2 rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) text-sm focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="flex items-center gap-2 pb-2 border-b border-(--color-gray)/20">
            <input type="checkbox" id="sablon-check-all" class="checkbox-custom" checked>
            <label for="sablon-check-all"
                class="text-sm font-medium text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                {{ __('bill-supplier.form.select_all') }}
            </label>
        </div>

        <div class="border border-(--color-gray)/20 rounded-xl p-3 max-h-70vh overflow-y-auto">
            <div id="sablon-modal-loading" class="hidden text-center text-sm text-(--color-gray) py-10">
                {{ __('bill-supplier.form.loading') }}
            </div>

            <div id="sablon-modal-empty" class="hidden text-center text-sm text-(--color-gray) py-10">
                {{ __('bill-supplier.form.empty') }}
            </div>

            <div id="sablon-modal-list" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
        </div>

        <small class="text-xs text-(--color-dark-gray) block">
            {{ $isEdit ? __('bill-supplier.form.hint_edit') : __('bill-supplier.form.hint_create') }}
            {{ __('bill-supplier.form.hint_only_done', ['status' => '<b>' . __('sablon.statuses.DONE') . '</b>']) }}
        </small>
    </div>

    <div class="lg:col-span-5 space-y-4">
        <div>
            <div class="mb-2 space-y-2">
                <label for="date_bill" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('bill-supplier.form.date_bill') }}
                </label>

                <input type="date" id="date_bill" name="date_bill"
                    value="{{ $isEdit ? $dateBill?->format('Y-m-d') : now()->format('Y-m-d') }}" required
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_paid" name="is_paid" value="1"
                    {{ $isEdit && $isPaid ? 'checked' : '' }} class="checkbox-custom">

                <label for="is_paid"
                    class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) ms-3 cursor-pointer">
                    {{ __('bill-supplier.form.is_paid') }}
                </label>
            </div>

            <div class="mb-2 space-y-2">
                <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('bill-supplier.form.notes') }}
                </label>

                <textarea id="notes" name="notes" rows="3"
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $isEdit ? $notes : '' }}</textarea>
            </div>
        </div>

        <div id="bs-calc-preview"
            class="hidden p-4 rounded-lg border border-(--color-primary)/30 bg-(--color-primary)/5 space-y-3 sticky top-4">
            <div>
                <p class="text-sm font-semibold text-(--color-dark) mb-2">{{ __('bill-supplier.form.preview_title') }}</p>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-(--color-dark)">{{ __('bill-supplier.form.calc_count') }}</p>
                        <p class="font-medium" id="calc-count">0 {{ __('bill-supplier.form.calc_count_suffix') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-dark)">{{ __('bill-supplier.form.calc_total_long_fabric') }}
                        </p>
                        <p class="font-medium" id="calc-total-long-fabric">0 m</p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-dark)">{{ __('bill-supplier.form.calc_total_fee') }}</p>
                        <p class="font-bold text-(--color-primary)" id="calc-total-fee">Rp0</p>
                    </div>
                </div>
            </div>
            <div id="calc-item-list" class="space-y-1 border-t border-(--color-dark)/20 pt-2 max-h-64 overflow-y-auto">
            </div>
        </div>
    </div>
</div>
