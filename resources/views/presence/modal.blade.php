<form id="presence-form">
    <x-modal id="hs-presence-modal" title="{{ __('presence.modal_title_prefix') }}" size="lg">
        <input type="hidden" id="employee_id" name="employee_id" />
        <input type="hidden" id="week_of" name="week_of" />

        <div class="grid grid-cols-2 gap-3">
            @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                <div class="group relative">
                    <label for="{{ $day }}"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __("presence.days.$day") }}
                        <span id="{{ $day }}-date" class="text-xs font-normal text-(--color-gray)"></span>
                    </label>
                    <input type="text" inputmode="numeric" id="{{ $day }}" name="{{ $day }}"
                        data-rupiah
                        class="day-input mt-1 px-4 py-2 block w-full rounded-lg
                            bg-(--color-light-gray) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)" />

                    <div class="hidden group-focus-within:flex flex-wrap gap-1 mt-1">
                        @foreach ([5000, 8000, 10000, 12000] as $quick)
                            <button type="button" data-quick-amount="{{ $quick }}"
                                data-target="#{{ $day }}"
                                class="btn-quick-amount px-2 py-0.5 text-[11px] rounded-md
                                    bg-(--color-light-gray) border border-(--color-gray)
                                    text-(--color-dark) hover:bg-(--color-gray)/40
                                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                                    cursor-pointer">
                                {{ number_format($quick, 0, ',', '.') }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('presence.notes') }}
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"></textarea>
        </div>

        <div class="flex justify-between items-center pt-2 border-t dark:border-(--color-gray)/30">
            <span class="font-bold">{{ __('presence.total') }}: <span id="modal-total">Rp0</span></span>
        </div>

        <x-slot:footer>
            <x-button-loading type="submit" text="{{ __('button-loading.Save') }}"
                loadingText="{{ __('button-loading.Saving...') }}"
                color="bg-(--color-success) hover:bg-(--color-success)/70"
                textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                rounded="rounded-lg" class="cursor-pointer" />
            <button type="button" data-hs-overlay="#hs-presence-modal"
                class="px-4 py-2 text-sm font-semibold rounded-lg
                    bg-(--color-danger) hover:bg-(--color-danger)/70
                    text-(--color-light) cursor-pointer hover:opacity-90 transition">
                {{ __('button-loading.Cancel') }}
            </button>
        </x-slot:footer>
    </x-modal>
</form>
