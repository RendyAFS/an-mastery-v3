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

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('fabric.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range" clearable="true"
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

        <x-datatable id="fabrics-datatable" filterId="filter-fabrics">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('fabric.fields.supplier') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('fabric.fields.stock_total') }}
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('fabric.fields.notes') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            <i data-lucide="settings" class="size-4"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>
@endsection
