@extends('layouts.main', ['title' => __('models.Fabric')])

@push('scripts')
    @vite('resources/js/pages/fabric/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Fabric') }}</h1>
                <p class="text-sm">{{ __('fabric.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-week-start"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('fabric.filter.week_start') }}
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
                        {{ __('fabric.filter.week_end') }}
                    </label>
                    <input type="week" id="filter-week-end"
                        class="form-input mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('fabric.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/80"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('fabrics.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                    bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
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
