@props([
    'id' => 'datatable',
    'search' => true,
    'length' => true,
    'filter' => true,
    'filterId' => 'dt-filter',
    'filterOptions' => [
        'all' => 'All',
        'active' => 'Active',
        'deleted' => 'Deleted',
    ],
    'filterDefault' => 'active',
    'lengthOptions' => [10, 20, 50],
    'defaultLength' => 10,
])

{{-- Top Bar --}}
<div class="flex flex-col gap-3 mb-4 sm:flex-row sm:justify-between sm:items-center">
    {{-- LEFT SIDE --}}
    <div class="flex items-center gap-3 w-full sm:w-auto">

        {{-- Search --}}
        @if ($search)
            <div class="relative w-full sm:w-64">
                <input type="text" id="dt-search"
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
            <select id="{{ $filterId }}" class="hidden w-24 sm:w-40"
                data-hs-select='{
                    "placeholder": "Filter",
                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                    "toggleClasses": "relative py-2 ps-4 pe-9 flex gap-x-2 w-24 cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                    "dropdownClasses": "mt-2 z-50 w-24 max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-y-auto",
                    "optionClasses": "ps-3 py-2 px-4 w-24 text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg",
                    "optionTemplate": "<div class=\"flex justify-between items-center w-24\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4\"></i></span></div>",
                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><i data-lucide=\"filter\" class=\"size-4\"></i></div>"
                }'>
                @foreach ($filterOptions as $value => $label)
                    <option value="{{ $value }}" @selected($value == $filterDefault)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        @endif
    </div>

    @if ($length)
        <div class="flex items-center gap-2 w-18 sm:w-auto justify-end">
            <span class="text-sm whitespace-nowrap">Show</span>
            <select id="dt-length" class="hidden w-18 sm:w-28"
                data-hs-select='{
                            "placeholder": "Show",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-4 pe-9 flex gap-x-2 text-nowrap w-18 cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                            "dropdownClasses": "mt-2 z-50 w-18 max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-(--color-gray) [&::-webkit-scrollbar-thumb]:bg-(--color-gray)",
                            "optionClasses": "ps-3 py-2 px-4 w-18 text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg focus:outline-hidden focus:bg-(--color-gray) hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-18\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></div>"
                        }'
                class="hidden py-2 px-3 text-sm rounded-lg border">
                @foreach ($lengthOptions as $opt)
                    <option value="{{ $opt }}" @selected($opt == $defaultLength)>
                        {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
</div>

{{-- Table --}}
<div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-10">
    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="min-w-full text-sm">
            {{ $slot }}
        </table>
    </div>
</div>

{{-- Footer --}}
<div class="flex flex-col gap-3 mt-4 sm:flex-row sm:justify-between sm:items-center">
    <div id="dt-info" class="text-sm text-center sm:text-left"></div>
    <div id="dt-pagination" class="flex flex-wrap justify-center gap-1 sm:justify-end"></div>
</div>

{{-- Pagination Templates (Hidden) --}}
<template id="dt-pagination-btn-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
        data-page=""></button>
</template>

<template id="dt-pagination-ellipsis-template">
    <span class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm text-(--color-gray)">
        …
    </span>
</template>

<template id="dt-pagination-prev-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none border border-transparent cursor-pointer"
        data-page="" aria-label="Previous">
        <i data-lucide="chevron-left" class="size-4"></i>
    </button>
</template>

<template id="dt-pagination-next-template">
    <button type="button"
        class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none border border-transparent cursor-pointer"
        data-page="" aria-label="Next">
        <i data-lucide="chevron-right" class="size-4"></i>
    </button>
</template>
