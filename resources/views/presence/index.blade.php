@extends('layouts.main', ['title' => __('models.Presence')])

@push('scripts')
    @vite('resources/js/pages/presence/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Presence') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('presence.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-week" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('presence.filter_week_label') }}
                    </label>
                    <input type="week" id="filter-week" value="2026-W26"
                        class="mt-1 px-4 py-2 block w-56 rounded-lg
                        bg-(--color-light) border border-(--color-gray)
                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                        dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>
                <button type="button" id="btn-bulk-generate"
                    class="flex items-center gap-2 py-2 px-4 rounded-lg bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="calendar-plus" class="size-4"></i>
                    {{ __('presence.generate_button') }}
                </button>
            </div>
        </div>

        <x-datatable id="presences-datatable" filterId="filter-presences" :defaultLength="-1" :filter="false"
            :lengthOptions="[-1]">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('presence.employee') }}
                    </th>
                    @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <th id="th-{{ $day }}"
                            class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                            <div class="flex flex-col items-center justify-center w-full">
                                <span>{{ __("presence.days.$day") }}</span>
                                <span class="th-date font-normal text-[10px] opacity-70"></span>
                            </div>
                        </th>
                    @endforeach
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">{{ __('presence.total') }}</div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>

    @include('presence.modal')
    @include('presence.bulk-generate-modal')
@endsection
