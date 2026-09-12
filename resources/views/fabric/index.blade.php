@extends('layouts.main', ['title' => __('models.Fabric')])

@push('scripts')
    @vite('resources/js/pages/fabric/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Fabric') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('fabric.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3" data-cg-page-filters="fabric-cardgrid">
                <div>
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('fabric.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)" roundedClass="rounded-lg" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('fabric.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/80"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('fabrics.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                    bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i>
                    {{ __('crud.add_title', ['model' => __('models.Fabric')]) }}
                </a>
            </div>
        </div>

        <x-cardgrid id="fabric-cardgrid" filterId="filter-fabric" :defaultLength="48" :lengthOptions="[12, 24, 48]" :filtersInline="false"
            gridCols="grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            <x-slot:filters>
                <div class="space-y-2">
                    <x-button-group id="filter-supplier" name="supplier_id" :options="$suppliers" :all-label="__('fabric.filter.all_suppliers')" layout="scroll"
                        :multiple="true" />
                    <x-button-group id="filter-type-fabric" name="type_fabric_id" :options="$typeFabrics" :all-label="__('fabric.filter.all_type_fabrics')" layout="scroll"
                        :multiple="true" />
                </div>
            </x-slot:filters>
        </x-cardgrid>
    </div>
@endsection
