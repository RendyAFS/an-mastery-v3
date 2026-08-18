@extends('layouts.main', ['title' => __('models.Memo')])

@push('scripts')
    @vite('resources/js/pages/memo/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Memo') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('memo.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('memo.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)" roundedClass="rounded-lg" />
                </div>

                <button type="button" id="btn-reset-filter"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg cursor-pointer
                        bg-(--color-danger) hover:bg-(--color-danger)/70 text-(--color-light)">
                    <i data-lucide="rotate-ccw" class="size-4"></i>
                    {{ __('memo.filter.reset') }}
                </button>

                <button type="button" id="btn-create-memo" aria-haspopup="dialog" aria-expanded="false"
                    aria-controls="hs-memo-modal" data-hs-overlay="#hs-memo-modal"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg cursor-pointer
                        bg-(--color-primary) hover:bg-(--color-primary)/80 text-white">
                    <i data-lucide="plus" class="size-4"></i>
                    {{ __('crud.add_title', ['model' => __('models.Memo')]) }}
                </button>
            </div>
        </div>

        <x-datatable id="memos-datatable" filterId="filter-memos" defaultLength="-1">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('memo.fields.employee') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('memo.fields.item_name') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('memo.fields.nominal') }}
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('memo.fields.status') }}
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            {{ __('memo.fields.date') }}
                        </div>
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

    @include('memo.modal')
@endsection
