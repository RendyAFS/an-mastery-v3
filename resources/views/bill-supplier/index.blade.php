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
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('bill-supplier.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range" clearable="true"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('bill-supplier.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />
            </div>
        </div>
        <x-cardgrid id="bill-supplier-cardgrid" :filter="false" :lengthOptions="[12, 24, 48]" :defaultLength="12" />
    </div>

    @include('bill-supplier.partials._modal-cover-style')
@endsection
