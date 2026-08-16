<x-modal id="hs-salary-employee-modal" title="{{ __('salary-employee.modal.title') }}" size="2xl">
    <input type="hidden" id="salary-employee-id" />
    <input type="hidden" id="salary-week-of" />
    <p class="text-lg font-semibold text-(--color-dark) dark:text-(--color-light) text-center">
        <span id="salary-employee-name">-</span>
    </p>

    <x-select id="modal-salary-status" name="modal-salary-status" label="{{ __('salary-employee.modal.status_label') }}"
        placeholder="{{ __('salary-employee.modal.status_placeholder') }}" :options="[
            'PENDING' => __('salary-employee.status.PENDING'),
            'PAID' => __('salary-employee.status.PAID'),
        ]" />

    <p id="salary-employee-locked-hint" class="hidden mt-1 text-xs text-(--color-warning)">
        {{ __('salary-employee.modal.locked_hint') }}
    </p>

    <div class="mt-4">
        <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('salary-employee.modal.additional_fee_label') }}
            </label>
            <button type="button" id="btn-add-additional-fee-row"
                class="inline-flex items-center gap-2 text-sm font-medium
                    text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i> {{ __('salary-employee.modal.add_fee') }}
            </button>
        </div>

        <div id="additional-fee-rows" class="space-y-4 mt-2"></div>
    </div>

    <x-slot:footer>
        <x-button-loading type="button" id="btn-save-salary-employee" :text="__('button-loading.Save')" :loadingText="__('button-loading.Saving...')"
            color="bg-(--color-success) hover:bg-(--color-success)/70"
            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
            rounded="rounded-lg" class="cursor-pointer" />

        <button type="button" data-hs-overlay="#hs-salary-employee-modal"
            class="px-4 py-2 text-sm font-semibold rounded-lg
                bg-(--color-danger) hover:bg-(--color-danger)/70
                text-(--color-light) cursor-pointer hover:opacity-90 transition">
            {{ __('button-loading.Cancel') }}
        </button>
    </x-slot:footer>
</x-modal>

<template id="additional-fee-row-template">
    <div
        class="additional-fee-row relative grid grid-cols-2 gap-3 p-3 rounded-lg border border-(--color-gray)/40 dark:border-(--color-dark-gray)">
        <button type="button"
            class="btn-remove-af-row absolute top-2 inset-e-2 p-1 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
            <i data-lucide="trash-2" class="size-3.5"></i>
        </button>

        <div class="col-span-1 group relative">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('salary-employee.modal.nominal') }}
            </label>
            <input type="text" inputmode="numeric" data-rupiah
                class="af-nominal mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">

            <div class="hidden group-focus-within:flex flex-wrap gap-1 mt-1">
                @foreach ([10000, 15000, 20000, 25000] as $quick)
                    <button type="button" data-quick-af-amount="{{ $quick }}"
                        class="btn-quick-af-amount px-2 py-0.5 text-[11px] rounded-md
                            bg-(--color-light-gray) border border-(--color-gray)
                            text-(--color-dark) hover:bg-(--color-gray)/40
                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                            cursor-pointer">
                        {{ number_format($quick, 0, ',', '.') }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('salary-employee.modal.notes') }}
            </label>
            <textarea placeholder="{{ __('salary-employee.modal.notes_placeholder') }}" rows="3" maxlength="255"
                class="af-notes mt-1 px-4 py-2 block w-full rounded-lg
                bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"></textarea>
        </div>
    </div>
</template>
