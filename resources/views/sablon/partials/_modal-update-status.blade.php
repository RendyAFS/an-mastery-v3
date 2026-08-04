<x-modal id="modal-update-status" title="{{ __('sablon.status_modal.title') }}" size="sm" :scrollable="false">
    <input type="hidden" id="status-sablon-id">

    <x-select id="modal-status" name="modal-status" label="{{ __('sablon.main_info.fields.status') }}"
        placeholder="{{ __('sablon.main_info.placeholders.status') }}" all :options="__('sablon.statuses')" dropdown-scope="window" />

    <x-slot:footer>
        <button type="button" data-hs-overlay="#modal-update-status"
            class="py-2 px-4 rounded-lg border border-(--color-gray) hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-slate) cursor-pointer">
            {{ __('sablon.status_modal.cancel') }}
        </button>

        <x-button-loading type="button" id="btn-save-status" :text="__('sablon.status_modal.save')" :loadingText="__('button-loading.Saving...')"
            color="bg-(--color-primary) hover:bg-(--color-primary)/80"
            textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
            rounded="rounded-lg" class="cursor-pointer" data-button-loading />
    </x-slot:footer>
</x-modal>
