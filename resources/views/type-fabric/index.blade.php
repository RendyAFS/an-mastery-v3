@extends('layouts.main', ['title' => __('models.TypeFabric')])

@push('scripts')
    @vite('resources/js/pages/type-fabric/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.TypeFabric') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('type-fabric.description') }}</p>
            </div>

            <button type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-type-fabric-modal"
                data-hs-overlay="#hs-type-fabric-modal" id="btn-create-type-fabric"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('crud.add_title', ['model' => __('models.TypeFabric')]) }}
            </button>
        </div>

        <x-datatable id="type-fabrics-datatable" filterId="filter-type-fabrics">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('type-fabric.fields.name') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('type-fabric.fields.notes') }}
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

    @include('type-fabric.modal')
@endsection
