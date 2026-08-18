@props([
    'id' => 'cardgrid',
    'search' => true,
    'length' => true,
    'filter' => true,
    'filterId' => 'cg-filter',
    'filterOptions' => [
        'all' => __('cardgrid.All'),
        'active' => __('cardgrid.Active'),
        'deleted' => __('cardgrid.Deleted'),
    ],
    'filterDefault' => 'active',
    'lengthOptions' => [12, 24, 48],
    'defaultLength' => 12,
    'filtersInline' => false,
])

{{-- Top Bar --}}
<div class="flex flex-col gap-3 mb-4" data-cg-controls="{{ $id }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
        {{-- LEFT SIDE --}}
        <div class="flex flex-wrap items-center gap-3 w-full sm:flex-1 sm:min-w-0">
            @if ($search)
                <div class="relative w-full sm:w-64">
                    <div class="absolute inset-y-0 inset-s-0 flex items-center pointer-events-none ps-4 z-10">
                        <i data-lucide="search" class="size-4"></i>
                    </div>

                    <input type="text" id="cg-search"
                        class="w-full ps-10 pe-10 py-2 px-3 text-sm rounded-lg
                        text-(--color-dark) dark:text-(--color-light)
                        border border-(--color-gray) dark:border-(--color-dark-gray)
                        bg-(--color-light) dark:bg-(--color-dark-slate)
                        focus:ring-2 focus:ring-(--color-primary)/30"
                        placeholder="{{ __('cardgrid.Search...') }}">

                    <button type="button" id="cg-search-clear"
                        class="absolute inset-y-0 inset-e-0 hidden items-center pe-3
                            text-(--color-gray)
                            hover:text-(--color-dark)
                            dark:hover:text-(--color-light)
                            transition cursor-pointer">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>
            @endif

            @if ($filter)
                <select id="{{ $filterId }}" class="hidden w-auto sm:w-40"
                    data-hs-select='{
                        "placeholder": "{{ __('cardgrid.Filter') }}",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                        "toggleClasses": "relative py-2 ps-4 pe-9 flex gap-x-2 w-auto cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                        "dropdownClasses": "mt-2 z-50 w-auto max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-y-auto",
                        "optionClasses": "ps-3 py-2 px-4 w-auto text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                        "optionTemplate": "<div class=\"flex justify-between items-center w-auto\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 inset-e-3 -translate-y-1/2\"><i data-lucide=\"filter\" class=\"size-4\"></i></div>"
                    }'>
                    @foreach ($filterOptions as $value => $label)
                        <option value="{{ $value }}" @selected($value == $filterDefault)>{{ $label }}</option>
                    @endforeach
                </select>
            @endif

            @isset($filters)
                @if ($filtersInline)
                    <div class="w-full sm:flex-1 sm:min-w-0">
                        {{ $filters }}
                    </div>
                @endif
            @endisset
        </div>

        {{-- RIGHT SIDE --}}
        @if ($length)
            <div class="flex items-center gap-2 w-auto sm:w-auto justify-end">
                <span class="text-sm whitespace-nowrap">{{ __('cardgrid.Show') }}</span>
                <select id="cg-length" class="hidden w-auto sm:w-28"
                    data-hs-select='{
                        "placeholder": "{{ __('cardgrid.Show') }}",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-4 pe-9 flex gap-x-2 text-nowrap w-auto cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                        "dropdownClasses": "mt-2 z-50 w-auto max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-hidden overflow-y-auto",
                        "optionClasses": "ps-3 py-2 px-4 w-auto text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                        "optionTemplate": "<div class=\"flex justify-between items-center w-auto\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 inset-e-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4\"></i></div>"
                    }'>
                    @foreach ($lengthOptions as $opt)
                        <option value="{{ $opt }}" @selected($opt == $defaultLength)>{{ $opt }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    {{-- EXTRA FILTERS (FULL WIDTH, ROW BARU) --}}
    @isset($filters)
        @unless ($filtersInline)
            <div class="w-full">
                {{ $filters }}
            </div>
        @endunless
    @endisset
</div>

{{-- Loading Overlay --}}
<div class="relative min-h-40">
    {{-- Loading Overlay --}}
    <div id="{{ $id }}-loading"
        class="hidden absolute inset-0 z-20 flex items-center justify-center rounded-xl bg-white/70 dark:bg-slate-900/70 backdrop-blur-[2px]">
        <div class="flex flex-col items-center gap-4">
            <div class="relative">
                <div class="size-12 rounded-full border-4 border-(--color-primary)/20"></div>

                <div
                    class="absolute inset-0 size-12 rounded-full border-4 border-transparent border-t-(--color-primary) animate-spin">
                </div>
            </div>

            <span class="text-sm text-(--color-dark) dark:text-(--color-light)">
                {{ __('cardgrid.Loading data...') }}
            </span>
        </div>
    </div>

    {{-- Card Grid Container --}}
    <div id="{{ $id }}"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 transition duration-200">
        {{-- Cards injected by JS --}}
    </div>
</div>

{{-- Card Grid Container --}}
<div id="{{ $id }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    {{-- Cards injected by JS --}}
</div>

{{-- Empty State --}}
<div id="{{ $id }}-empty" class="hidden">
    <div class="flex flex-col items-center justify-center py-20 text-(--color-gray)">
        <i data-lucide="inbox" class="size-12 mb-3"></i>
        <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('cardgrid.No data found') }}</p>
    </div>
</div>

{{-- Footer --}}
<div class="flex flex-col gap-3 mt-4 sm:flex-row sm:justify-between sm:items-center">
    <div id="cg-info" class="text-sm text-center sm:text-left"></div>
    <div id="cg-pagination" class="flex flex-wrap justify-center gap-1 sm:justify-end"></div>
</div>

{{-- Pagination Templates --}}
<template id="cg-pagination-btn-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
        data-page=""></button>
</template>
<template id="cg-pagination-ellipsis-template">
    <span class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm text-(--color-gray)">…</span>
</template>
<template id="cg-pagination-prev-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none border border-transparent cursor-pointer"
        data-page="" aria-label="{{ __('cardgrid.Previous') }}">
        <i data-lucide="chevron-left" class="size-4"></i>
    </button>
</template>
<template id="cg-pagination-next-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none border border-transparent cursor-pointer"
        data-page="" aria-label="{{ __('cardgrid.Next') }}">
        <i data-lucide="chevron-right" class="size-4"></i>
    </button>
</template>

<script>
    window.cardgridLang = window.cardgridLang || {};
    window.cardgridLang['{{ $id }}'] = {
        noResults: @json(__('cardgrid.No results')),
        showing: @json(__('cardgrid.Showing :from - :to of :total')),
    };
</script>
