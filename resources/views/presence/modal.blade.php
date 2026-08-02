<div id="hs-presence-modal"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-presence-modal-label">

    <div
        class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">

        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl pointer-events-auto
            dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div
                class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                <h3 id="hs-presence-modal-label" class="font-semibold text-(--color-dark) dark:text-(--color-light)">
                    Presence
                </h3>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full
                        bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) hover:bg-(--color-gray)/40
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                        focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                    aria-label="Close" data-hs-overlay="#hs-presence-modal">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="text-(--color-dark)/80 dark:text-(--color-light)/80 size-5"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto">
                <form id="presence-form" class="space-y-4">
                    <input type="hidden" id="employee_id" name="employee_id" />
                    <input type="hidden" id="week_of" name="week_of" />

                    {{-- Generate section --}}
                    <div>
                        <label for="generate_value"
                            class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            Nominal per day
                        </label>

                        <div class="flex gap-2 mt-1">
                            <input type="text" inputmode="numeric" id="generate_value" data-rupiah value="0"
                                class="flex-1 px-4 py-2 rounded-lg
                                    bg-(--color-light-gray) border border-(--color-gray)
                                    text-(--color-dark)
                                    focus:border-(--color-primary)
                                    focus:ring focus:ring-(--color-primary)/30
                                    dark:bg-(--color-dark-slate)
                                    dark:border-(--color-slate)
                                    dark:text-(--color-light)" />

                            <button type="button" id="btn-generate"
                                class="px-4 py-2 rounded-lg bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer whitespace-nowrap">
                                Generate
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ([5000, 8000, 10000, 12000] as $quick)
                                <button type="button" data-quick-amount="{{ $quick }}"
                                    data-target="#generate_value"
                                    class="btn-quick-amount px-3 py-1 text-xs rounded-lg
                                        bg-(--color-light-gray) border border-(--color-gray)
                                        text-(--color-dark)
                                        hover:bg-(--color-gray)/40
                                        dark:bg-(--color-dark-slate)
                                        dark:border-(--color-slate)
                                        dark:text-(--color-light)
                                        cursor-pointer">
                                    {{ number_format($quick, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    @php
                        $dayLabels = [
                            'monday' => 'Senin',
                            'tuesday' => 'Selasa',
                            'wednesday' => 'Rabu',
                            'thursday' => 'Kamis',
                            'friday' => 'Jumat',
                            'saturday' => 'Sabtu',
                            'sunday' => 'Minggu',
                        ];
                    @endphp

                    <div class="grid grid-cols-2 gap-3">
                        @foreach ($dayLabels as $day => $label)
                            <div>
                                <label for="{{ $day }}"
                                    class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                                    {{ $label }}
                                    <span id="{{ $day }}-date" class="text-xs font-normal text-(--color-gray)">
                                    </span>
                                </label>
                                <input type="text" inputmode="numeric" id="{{ $day }}"
                                    name="{{ $day }}" data-rupiah
                                    class="day-input mt-1 px-4 py-2 block w-full rounded-lg
                                        bg-(--color-light-gray) border border-(--color-gray)
                                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)" />
                            </div>
                        @endforeach
                    </div>

                    <div class="col-span-full">
                        <label for="notes"
                            class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            Notes
                        </label>
                        <textarea id="notes" name="notes" rows="3"
                            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"></textarea>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t dark:border-(--color-gray)/30">
                        <span class="font-bold">Total: <span id="modal-total">Rp0</span></span>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-button-loading type="submit" text="Save" loadingText="Saving..."
                            color="bg-(--color-success) hover:bg-(--color-success)/70"
                            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                            rounded="rounded-lg" class="cursor-pointer" />

                        <button type="button" data-hs-overlay="#hs-presence-modal"
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
