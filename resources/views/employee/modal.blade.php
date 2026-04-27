<div id="hs-employee-modal"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-employee-modal-label">

    <div
        class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">

        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl pointer-events-auto
            dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div
                class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                <h3 id="hs-employee-modal-label" class="font-semibold text-(--color-dark) dark:text-(--color-light)">
                    Color Fabric
                </h3>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full
                        bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) hover:bg-(--color-gray)/40
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                        focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none"
                    aria-label="Close" data-hs-overlay="#hs-employee-modal">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto">
                <form id="employee-form" data-mode="create">
                    @include('employee.form', ['employee' => null])

                    {{-- Footer --}}
                    <div class="flex gap-2 pt-2">
                        <x-button-loading type="submit" text="Save" loadingText="Saving..."
                            color="bg-(--color-success) hover:bg-(--color-success)/70"
                            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                            rounded="rounded-lg" class="cursor-pointer" />

                        <button type="button" data-hs-overlay="#hs-employee-modal"
                            class="px-4 py-2 text-sm font-semibold rounded-lg
                                bg-(--color-danger) hover:bg-(--color-danger)/70
                                text-(--color-light) cursor-pointer hover:opacity-90 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
