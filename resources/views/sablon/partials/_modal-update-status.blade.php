<x-modal id="modal-update-status" title="{{ __('sablon.status_modal.title') }}" size="md" :scrollable="false">
    <input type="hidden" id="status-sablon-id">
    <input type="hidden" id="status-sablon-price" value="0">

    <div class="space-y-4">
        <div>
            <x-select id="modal-status" name="modal-status" label="{{ __('sablon.main_info.fields.status') }}"
                placeholder="{{ __('sablon.main_info.placeholders.status') }}" all :options="__('sablon.statuses')" dropdown-scope="window" />
        </div>

        <div id="status-modal-fabric-section" class="pt-3 border-t border-(--color-gray)/20">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-semibold uppercase tracking-wider text-(--color-dark-gray)">
                    {{ __('sablon.long_fabric_modal.title') }}
                </label>
                <span id="status-modal-sablon-info" class="text-xs text-(--color-dark-gray) truncate max-w-[220px]"></span>
            </div>

            <div id="status-modal-fabric-items" class="space-y-2 max-h-[220px] overflow-y-auto pr-1"></div>

            <div class="mt-3 pt-2.5 border-t border-(--color-gray)/20 space-y-1 text-xs">
                <div class="flex justify-between items-center font-medium">
                    <span>{{ __('sablon.long_fabric_modal.total') }}:</span>
                    <span id="status-modal-total-preview" class="font-bold text-sm">0 m</span>
                </div>
                <div class="flex justify-between items-center text-(--color-dark-gray)">
                    <span>{{ __('sablon.long_fabric_modal.total_sablon') }}:</span>
                    <span id="status-modal-total-sablon-preview" class="font-semibold text-(--color-dark) dark:text-(--color-light)">Rp 0</span>
                </div>
            </div>
        </div>
    </div>

    <x-slot:footer>
        <button type="button" data-hs-overlay="#modal-update-status"
            class="py-2 px-4 rounded-lg border border-(--color-gray) hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer text-sm">
            {{ __('sablon.status_modal.cancel') }}
        </button>

        <x-button-loading type="button" id="btn-save-status" :text="__('sablon.status_modal.save')" :loadingText="__('button-loading.Saving...')"
            color="bg-(--color-primary) hover:bg-(--color-primary)/80"
            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-sm"
            rounded="rounded-lg" class="cursor-pointer" data-button-loading />
    </x-slot:footer>
</x-modal>
