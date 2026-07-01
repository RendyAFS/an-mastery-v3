<div id="modal-update-status"
    class="hs-overlay hidden size-full fixed top-0 inset-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-(--color-light) dark:bg-(--color-dark) border dark:border-(--color-gray)/30 shadow-md rounded-xl pointer-events-auto">
            {{-- Header --}}
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-(--color-gray)/30">
                <h3 class="font-bold">
                    Update Status
                </h3>

                <button type="button" class="cursor-pointer" data-hs-overlay="#modal-update-status">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4">
                <input type="hidden" id="status-sablon-id">

                <x-select id="modal-status" name="modal-status" label="Status" placeholder="Choose Status" all
                    :options="[
                        'ON_PROGRESS' => 'On Progress',
                        'DONE' => 'Done',
                        'DELIVERED' => 'Delivered',
                        'RETURNED' => 'Returned',
                    ]" />
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 py-3 px-4 border-t dark:border-(--color-gray)/30">
                <button type="button" data-hs-overlay="#modal-update-status"
                    class="py-2 px-4 rounded-lg border border-(--color-gray) hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer">
                    Cancel
                </button>

                <button type="button" id="btn-save-status" data-button-loading
                    class="py-2 px-4 rounded-lg bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                    Save
                </button>
            </div>
        </div>
    </div>
</div>
