<div id="hs-bulk-generate-modal"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="hs-bulk-generate-modal-label">

    <div
        class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">

        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl pointer-events-auto
            dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div
                class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                <h3 id="hs-bulk-generate-modal-label" class="font-semibold text-(--color-dark) dark:text-(--color-light)">
                    {{ __('presence.bulk.title') }}
                </h3>
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full
                        bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) hover:bg-(--color-gray)/40
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                        focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                    aria-label="Close" data-hs-overlay="#hs-bulk-generate-modal">
                    <span class="sr-only">Close</span>
                    <i data-lucide="x" class="text-(--color-dark)/80 dark:text-(--color-light)/80 size-5"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="p-4 overflow-y-auto">
                <form id="bulk-generate-form" class="space-y-4">
                    <div>
                        <label for="bulk_week_of"
                            class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            {{ __('presence.bulk.week_of') }}
                        </label>
                        <x-datepicker id="bulk_week_of" name="week_of" mode="range" weekRange="true"/>
                    </div>

                    <div>
                        <label for="bulk_amount"
                            class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            {{ __('presence.bulk.nominal_per_day') }}
                        </label>
                        <input type="text" inputmode="numeric" id="bulk_amount" name="amount" data-rupiah
                            value="0"
                            class="mt-1 px-4 py-2 block w-full rounded-lg
                                bg-(--color-light-gray) border border-(--color-gray)
                                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)" />
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach ([5000, 8000, 10000, 12000] as $quick)
                                <button type="button" data-quick-amount="{{ $quick }}"
                                    data-target="#bulk_amount"
                                    class="btn-quick-amount px-3 py-1 text-xs rounded-lg
                                        bg-(--color-light-gray) border border-(--color-gray)
                                        text-(--color-dark) hover:bg-(--color-gray)/40
                                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                                        cursor-pointer">
                                    {{ number_format($quick, 0, ',', '.') }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                                {{ __('presence.bulk.days') }}
                            </label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" id="bulk_check_all_days" class="checkbox-custom" checked />
                                {{ __('presence.bulk.select_all_days') }}
                            </label>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach ([
        'monday' => __('presence.days.monday'),
        'tuesday' => __('presence.days.tuesday'),
        'wednesday' => __('presence.days.wednesday'),
        'thursday' => __('presence.days.thursday'),
        'friday' => __('presence.days.friday'),
        'saturday' => __('presence.days.saturday'),
        'sunday' => __('presence.days.sunday'),
    ] as $key => $label)
                                <label
                                    class="flex items-center gap-1.5 px-3 py-1.5 text-xs rounded-lg
                                    border border-(--color-gray) dark:border-(--color-slate) cursor-pointer">
                                    <input type="checkbox" class="bulk-day-checkbox checkbox-custom"
                                        data-day="{{ $key }}" value="{{ $key }}"
                                        {{ $key !== 'sunday' ? 'checked' : '' }} />
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                                {{ __('presence.bulk.employees') }}
                            </label>
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" id="bulk_check_all" class="checkbox-custom" />
                                {{ __('presence.bulk.select_all') }}
                            </label>
                        </div>
                        <div id="bulk_employee_list"
                            class="max-h-56 overflow-y-auto border border-(--color-gray) dark:border-(--color-slate) rounded-lg divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)">
                            {{-- populated via JS --}}
                        </div>
                    </div>

                    <div class="flex gap-2 pt-2">
                        <x-button-loading type="submit" text="{{ __('presence.generate') }}"
                            loadingText="{{ __('button-loading.Saving...') }}"
                            color="bg-(--color-success) hover:bg-(--color-success)/70"
                            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                            rounded="rounded-lg" class="cursor-pointer" />

                        <button type="button" data-hs-overlay="#hs-bulk-generate-modal"
                            class="px-4 py-2 text-sm font-semibold rounded-lg
                                bg-(--color-danger) hover:bg-(--color-danger)/70
                                text-(--color-light) cursor-pointer hover:opacity-90 transition">
                            {{ __('button-loading.Cancel') }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
