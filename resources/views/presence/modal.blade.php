 <div id="hs-presence-modal"
     class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
     <div
         class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
         <div
             class="flex flex-col bg-(--color-light) dark:bg-(--color-dark) border dark:border-(--color-gray)/30 shadow-md rounded-xl pointer-events-auto">
             <div class="flex justify-between items-center py-3 px-4 border-b dark:border-(--color-gray)/30">
                 <h3 id="hs-presence-modal-label" class="font-bold">Presence</h3>
                 <button type="button" class="cursor-pointer" data-hs-overlay="#hs-presence-modal">
                     <i data-lucide="x" class="size-4"></i>
                 </button>
             </div>

             <form id="presence-form" class="p-4 space-y-4">
                 <input type="hidden" id="employee_id" name="employee_id" />
                 <input type="hidden" id="week_of" name="week_of" />

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
                     <button type="submit" data-button-loading
                         class="py-2 px-4 rounded-lg bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                         Save
                     </button>
                 </div>
             </form>
         </div>
     </div>
 </div>
