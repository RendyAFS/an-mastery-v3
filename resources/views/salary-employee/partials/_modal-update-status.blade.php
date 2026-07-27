<div id="modal-update-status-salary"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl
            dark:bg-(--color-dark) dark:border-(--color-slate)">
            <div class="flex justify-between items-center py-3 px-4 border-b border-(--color-gray)/20">
                <h3 class="font-bold text-(--color-dark) dark:text-(--color-light)">Update Status</h3>
                <button type="button" class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer"
                    data-hs-overlay="#modal-update-status-salary">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            <div class="p-4">
                <input type="hidden" id="status-salary-id">

                <x-select id="modal-salary-status" name="modal-salary-status" label="Status" placeholder="Choose Status"
                    :options="[
                        'PENDING' => 'Pending',
                        'DONE' => 'Done',
                    ]" />
            </div>

            <div class="flex justify-end gap-2 p-4 border-t border-(--color-gray)/20">
                <button type="button"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-(--color-light-gray) hover:bg-(--color-gray)/40 cursor-pointer"
                    data-hs-overlay="#modal-update-status-salary">
                    Cancel
                </button>
                <button type="button" id="btn-save-status-salary"
                    class="px-4 py-2 text-sm font-semibold rounded-lg bg-(--color-success) hover:bg-(--color-success)/70 text-(--color-light) cursor-pointer">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
