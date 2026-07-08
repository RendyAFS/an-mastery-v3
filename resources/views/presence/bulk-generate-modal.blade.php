<div id="hs-bulk-generate-modal"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-(--color-light) dark:bg-(--color-dark) border dark:border-(--color-gray)/30 shadow-md rounded-xl pointer-events-auto">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-(--color-gray)/30">
                <h3 class="font-bold">Generate Presence</h3>
                <button type="button" class="cursor-pointer" data-hs-overlay="#hs-bulk-generate-modal">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            <form id="bulk-generate-form" class="p-4 space-y-4">
                <div>
                    <label for="bulk_week_of"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Week Of
                    </label>
                    <input type="week" id="bulk_week_of" name="week_of"
                        class="mt-1 px-4 py-2 block w-full rounded-lg
                            bg-(--color-light-gray) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <div>
                    <label for="bulk_amount"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Nominal per day
                    </label>
                    <input type="text" inputmode="numeric" id="bulk_amount" name="amount" data-rupiah value="10000"
                        class="mt-1 px-4 py-2 block w-full rounded-lg
                            bg-(--color-light-gray) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            Employees
                        </label>
                        <label class="flex items-center gap-2 text-sm cursor-pointer">
                            <input type="checkbox" id="bulk_check_all" class="checkbox-custom" />
                            Select all
                        </label>
                    </div>
                    <div id="bulk_employee_list"
                        class="max-h-56 overflow-y-auto border border-(--color-gray) dark:border-(--color-slate) rounded-lg divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)">
                        {{-- populated via JS --}}
                    </div>
                </div>

                <div class="flex justify-end pt-2 border-t dark:border-(--color-gray)/30">
                    <button type="submit" data-button-loading
                        class="py-2 px-4 rounded-lg bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                        Generate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
