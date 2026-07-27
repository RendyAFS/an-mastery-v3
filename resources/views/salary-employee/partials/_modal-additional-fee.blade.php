<div id="modal-additional-fee"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="modal-additional-fee-label">

    <div
        class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all sm:max-w-xl sm:w-full m-3 sm:mx-auto">

        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl pointer-events-auto
            dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div
                class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                <h3 id="modal-additional-fee-label" class="font-semibold text-(--color-dark) dark:text-(--color-light)">
                    Additional Fee
                </h3>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full
                        bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) hover:bg-(--color-gray)/40
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                        focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                    aria-label="Close" data-hs-overlay="#modal-additional-fee">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="text-(--color-dark)/80 dark:text-(--color-light)/80 size-5"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto">
                <input type="hidden" id="additional-fee-salary-id" />

                <div id="additional-fee-rows" class="space-y-4"></div>

                <button type="button" id="btn-add-additional-fee-row"
                    class="mt-3 inline-flex items-center gap-2 text-sm font-medium
                        text-(--color-primary) hover:opacity-80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i> Add Fee
                </button>

                {{-- Footer --}}
                <div class="flex gap-2 pt-5">
                    <x-button-loading type="button" id="btn-save-additional-fee" text="Save" loadingText="Saving..."
                        color="bg-(--color-success) hover:bg-(--color-success)/70"
                        textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                        rounded="rounded-lg" class="cursor-pointer" />

                    <button type="button" data-hs-overlay="#modal-additional-fee"
                        class="px-4 py-2 text-sm font-semibold rounded-lg
                            bg-(--color-danger) hover:bg-(--color-danger)/70
                            text-(--color-light) cursor-pointer hover:opacity-90 transition">
                        Cancel
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<template id="additional-fee-row-template">
    <div
        class="additional-fee-row relative grid grid-cols-2 gap-3 p-3 rounded-lg border border-(--color-gray)/40 dark:border-(--color-dark-gray)">
        <button type="button"
            class="btn-remove-af-row absolute top-2 inset-e-2 p-1 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
            <i data-lucide="trash-2" class="size-3.5"></i>
        </button>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Nominal
            </label>
            <input type="text" inputmode="numeric" data-rupiah
                class="af-nominal mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="col-span-1">
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Notes
            </label>
            <input type="text" placeholder="Notes"
                class="af-notes mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>
    </div>
</template>
