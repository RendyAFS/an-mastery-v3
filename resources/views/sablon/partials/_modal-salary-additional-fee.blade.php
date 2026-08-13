<x-modal id="hs-sablon-salary-fee-modal" title="{{ __('sablon.employee_detail.salary_fee_modal_title') }}" size="2xl">
    <input type="hidden" id="sablon-salary-fee-employee-id" />
    <input type="hidden" id="sablon-salary-fee-week-of" />
    <input type="hidden" id="sablon-salary-fee-status" />

    <p class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light)">
        <span id="sablon-salary-fee-employee-name">-</span>
    </p>
    <p class="text-xs text-(--color-dark-gray) mt-1" id="sablon-salary-fee-week-label"></p>

    <div class="mt-4">
        <div class="flex items-center justify-between">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('sablon.employee_detail.additional_fee_label') }}
            </label>
            <button type="button" id="btn-add-sablon-salary-fee-row"
                class="inline-flex items-center gap-2 text-sm font-medium
                    text-(--color-primary) hover:opacity-80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i> {{ __('sablon.employee_detail.add_fee') }}
            </button>
        </div>

        <div id="sablon-salary-fee-rows" class="space-y-4 mt-2"></div>
    </div>

    <x-slot:footer>
        <x-button-loading type="button" id="btn-save-sablon-salary-fee" :text="__('button-loading.Save')" :loadingText="__('button-loading.Saving...')"
            color="bg-(--color-success) hover:bg-(--color-success)/70"
            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
            rounded="rounded-lg" class="cursor-pointer" />

        <button type="button" data-hs-overlay="#hs-sablon-salary-fee-modal"
            class="px-4 py-2 text-sm font-semibold rounded-lg
                bg-(--color-danger) hover:bg-(--color-danger)/70
                text-(--color-light) cursor-pointer hover:opacity-90 transition">
            {{ __('button-loading.Cancel') }}
        </button>
    </x-slot:footer>
</x-modal>

<template id="sablon-salary-fee-row-template">
    <div
        class="sablon-salary-fee-row relative grid grid-cols-2 gap-3 p-3 rounded-lg border border-(--color-gray)/40 dark:border-(--color-dark-gray)">
        <button type="button"
            class="btn-remove-sablon-salary-fee-row absolute top-2 inset-e-2 p-1 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
            <i data-lucide="trash-2" class="size-3.5"></i>
        </button>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('salary-employee.modal.nominal') }}
            </label>
            <input type="text" inputmode="numeric" data-rupiah
                class="sf-nominal mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('salary-employee.modal.notes') }}
            </label>
            <textarea placeholder="{{ __('salary-employee.modal.notes_placeholder') }}" rows="3" maxlength="255"
                class="sf-notes mt-1 px-4 py-2 block w-full rounded-lg
                bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"></textarea>
        </div>
    </div>
</template>
