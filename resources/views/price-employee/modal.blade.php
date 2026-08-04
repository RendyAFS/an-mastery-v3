<form id="price-employee-form" data-mode="create">
    <x-modal id="hs-price-employee-modal" title="{{ __('models.PriceEmployee') }}" size="lg">
        @include('price-employee.form', ['priceEmployee' => null])

        <x-slot:footer>
            <x-button-loading type="submit" text="{{ __('button-loading.Save') }}"
                loadingText="{{ __('button-loading.Saving...') }}"
                color="bg-(--color-success) hover:bg-(--color-success)/70"
                textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                rounded="rounded-lg" class="cursor-pointer" />

            <button type="button" data-hs-overlay="#hs-price-employee-modal"
                class="px-4 py-2 text-sm font-semibold rounded-lg
                    bg-(--color-danger) hover:bg-(--color-danger)/70
                    text-(--color-light) cursor-pointer hover:opacity-90 transition">
                {{ __('button-loading.Cancel') }}
            </button>
        </x-slot:footer>
    </x-modal>
</form>
