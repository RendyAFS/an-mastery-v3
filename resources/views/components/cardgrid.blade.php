@props([
    'id' => 'cardgrid',
    'search' => true,
    'length' => true,
    'filter' => true,
    'filterId' => 'cg-filter',
    'filterOptions' => [
        'all' => 'All',
        'active' => 'Active',
        'deleted' => 'Deleted',
    ],
    'filterDefault' => 'active',
    'lengthOptions' => [12, 24, 48],
    'defaultLength' => 12,
])

{{-- Top Bar --}}
<div class="flex flex-col gap-3 mb-4 sm:flex-row sm:justify-between sm:items-center">
    {{-- LEFT SIDE --}}
    <div class="flex items-center gap-3 w-full sm:w-auto">

        {{-- Search --}}
        @if ($search)
            <div class="relative w-full sm:w-64">
                <input type="text" id="cg-search"
                    class="w-full ps-10 py-2 px-3 text-sm rounded-lg
                    text-(--color-dark) dark:text-(--color-light)
                    border border-(--color-gray) dark:border-(--color-dark-gray)
                    bg-(--color-light) dark:bg-(--color-dark-slate)
                    focus:ring-2 focus:ring-(--color-primary)/30"
                    placeholder="Search...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4"></i>
                </div>
            </div>
        @endif

        {{-- Global Filter --}}
        @if ($filter)
            <select id="{{ $filterId }}" class="hidden w-auto sm:w-40"
                data-hs-select='{
                    "placeholder": "Filter",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "relative py-2 ps-4 pe-9 flex gap-x-2 w-auto cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                    "dropdownClasses": "mt-2 z-50 w-auto max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-y-auto",
                    "optionClasses": "ps-3 py-2 px-4 w-auto text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-auto\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><i data-lucide=\"filter\" class=\"size-4\"></i></div>"
                }'>
                @foreach ($filterOptions as $value => $label)
                    <option value="{{ $value }}" @selected($value == $filterDefault)>{{ $label }}</option>
                @endforeach
            </select>
        @endif
    </div>

    @if ($length)
        <div class="flex items-center gap-2 w-auto sm:w-auto justify-end">
            <span class="text-sm whitespace-nowrap">Show</span>
            <select id="cg-length" class="hidden w-auto sm:w-28"
                data-hs-select='{
                    "placeholder": "Show",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-4 pe-9 flex gap-x-2 text-nowrap w-auto cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                    "dropdownClasses": "mt-2 z-50 w-auto max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-hidden overflow-y-auto",
                    "optionClasses": "ps-3 py-2 px-4 w-auto text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-auto\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4\"></i></div>"
                }'>
                @foreach ($lengthOptions as $opt)
                    <option value="{{ $opt }}" @selected($opt == $defaultLength)>{{ $opt }}</option>
                @endforeach
            </select>
        </div>
    @endif
</div>

{{-- Loading Overlay --}}
<div id="{{ $id }}-loading" class="hidden">
    <div class="flex justify-center items-center py-20">
        <i data-lucide="loader-circle" class="size-8 animate-spin text-(--color-primary)"></i>
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
        <p class="text-sm">No data found</p>
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
        data-page="" aria-label="Previous">
        <i data-lucide="chevron-left" class="size-4"></i>
    </button>
</template>
<template id="cg-pagination-next-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none border border-transparent cursor-pointer"
        data-page="" aria-label="Next">
        <i data-lucide="chevron-right" class="size-4"></i>
    </button>
</template>
