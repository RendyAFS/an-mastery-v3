@extends('layouts.main', ['title' => __('models.Bonus')])

@push('scripts')
    @vite('resources/js/pages/bonus/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Bonus') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('bonus.description') }}</p>
            </div>

            <button type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-bonus-modal"
                data-hs-overlay="#hs-bonus-modal" id="btn-create-bonus"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('crud.add_title', ['model' => __('models.Bonus')]) }}
            </button>
        </div>

        <x-datatable id="bonuses-datatable" filterId="filter-bonuses">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('bonus.fields.min') }}
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('bonus.fields.bonus') }}
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('bonus.fields.notes') }}
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

    @include('bonus.modal')
@endsection
