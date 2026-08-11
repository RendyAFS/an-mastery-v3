@extends('layouts.main', ['title' => __('models.BillSupplier')])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.BillSupplier') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('bill-supplier.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-week-start"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('bill-supplier.filter.week_start') }}
                    </label>
                    <input type="week" id="filter-week-start"
                        class="form-input mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <div>
                    <label for="filter-week-end"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('bill-supplier.filter.week_end') }}
                    </label>
                    <input type="week" id="filter-week-end"
                        class="form-input mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('bill-supplier.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer mt-6" />
            </div>
        </div>
        <x-cardgrid id="bill-supplier-cardgrid" :filter="false" :lengthOptions="[12, 24, 48]" :defaultLength="12" />
    </div>

    @include('bill-supplier.partials._modal-cover-style')
@endsection
