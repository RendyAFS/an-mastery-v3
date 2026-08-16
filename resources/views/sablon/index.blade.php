@extends('layouts.main', ['title' => __('models.Sablon')])

@push('scripts')
    @vite('resources/js/pages/sablon/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Sablon') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('sablon.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('sablon.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range" clearable="true"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('sablon.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/80"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('sablons.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i>
                    {{ __('crud.add_title', ['model' => __('models.Sablon')]) }}
                </a>
            </div>
        </div>

        <x-cardgrid id="sablon-cardgrid" filterId="filter-sablon" :defaultLength="48" :lengthOptions="[12, 24, 48]" :filtersInline="false">
            <x-slot:filters>
                <x-button-group id="filter-supplier" name="supplier_id" :options="$suppliers" :all-label="__('sablon.filter.all_suppliers')" layout="scroll"
                    :multiple="true" />
            </x-slot:filters>
        </x-cardgrid>
    </div>

    @include('sablon.partials._modal-update-status')
@endsection
