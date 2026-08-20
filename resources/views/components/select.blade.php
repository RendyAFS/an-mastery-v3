@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Choose data',
    'searchPlaceholder' => 'Search...',
    'id' => $name,
    'clearable' => false,
    'multiple' => false,
    'search' => true,
    'bgClass' => 'bg-(--color-light-gray) dark:bg-(--color-dark-slate)',
    'wrapperClass' => 'w-full',
    'dropdownZIndex' => null,
    'toggleClasses' => null,
    'dropdownClasses' => null,

    // khusus mode API (opsional)
    'apiUrl' => null,
    'apiDataPart' => 'results',
    'perPage' => 10,
    'scrollThreshold' => 100,
    'fieldId' => 'id',
    'fieldTitle' => 'name',
    'fieldPage' => 'page',
    'searchQueryKey' => 'search',
    'dropdownScope' => null,
    'dropdownMaxH' => 'max-h-48',
])

@php
    $isApi = !empty($apiUrl);

    $normalizedOptions = [];
    if (is_iterable($options)) {
        foreach ($options as $key => $val) {
            if ($val instanceof \UnitEnum) {
                $enumVal = property_exists($val, 'value') ? $val->value : $val->name;
                $enumLabel = method_exists($val, 'label') ? $val->label() : $enumVal;
                $normalizedOptions[$enumVal] = $enumLabel;
            } elseif (is_numeric($key) && is_string($val)) {
                $normalizedOptions[$val] = $val;
            } else {
                $normalizedOptions[$key] = (string) $val;
            }
        }
    }

    $defaultToggleClasses = "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2.5 ps-4 " . ($clearable ? 'pe-16' : 'pe-9') . " flex w-full cursor-pointer {$bgClass} border border-(--color-gray) rounded-lg text-start text-sm text-(--color-dark) focus:outline-hidden focus:ring-2 focus:ring-(--color-primary)/30 dark:border-(--color-slate) dark:text-(--color-light)";
    $defaultDropdownClasses = "mt-2 max-h-60 p-1 " . ($dropdownZIndex ?? ($dropdownScope ? 'z-90' : ($isApi ? 'z-80' : 'z-50'))) . " w-full bg-(--color-light-gray) dark:bg-(--color-dark-slate) border border-(--color-gray) rounded-lg overflow-y-auto dark:border-(--color-slate)";
@endphp

@if ($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light) mb-1">
        {{ $label }}
    </label>
@endif

<div class="relative {{ $wrapperClass }}">
    <select name="{{ $name }}" id="{{ $id }}" {{ $multiple ? 'multiple' : '' }}
        @if ($isApi && $value) data-default-value="{{ $value }}" @endif
        data-hs-select='{
            @if ($isApi) "apiUrl": "{{ $apiUrl }}",
                "apiDataPart": "{{ $apiDataPart }}",
                "apiLoadMore": {
                    "perPage": {{ $perPage }},
                    "scrollThreshold": {{ $scrollThreshold }}
                },
                "apiFieldsMap": {
                    "id": "{{ $fieldId }}",
                    "title": "{{ $fieldTitle }}",
                    "val": "{{ $fieldId }}",
                    "page": "{{ $fieldPage }}"
                },
                "apiSearchQueryKey": "{{ $searchQueryKey }}", @endif
            {{ $dropdownScope ? '"dropdownScope": "' . $dropdownScope . '",' : '' }}
            "hasSearch": {{ $search ? 'true' : 'false' }},
            "searchPlaceholder": "{{ $searchPlaceholder }}",
            "placeholder": "{{ $placeholder }}",
            "toggleClasses": "{{ $toggleClasses ?? $defaultToggleClasses }}",
            "dropdownClasses": "{{ $dropdownClasses ?? $defaultDropdownClasses }}",
            "searchWrapperClasses": "sticky top-0 z-10 p-2 bg-(--color-light-gray) dark:bg-(--color-dark-slate)",
            "searchClasses": "px-4 py-2 block w-full text-sm rounded-md border border-(--color-gray) bg-(--color-light-gray) text-(--color-dark) placeholder:text-(--color-dark)/60 dark:placeholder:text-(--color-light)/60 focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30 dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)",
            "optionClasses": "py-2 px-4 w-full text-sm text-black dark:text-white cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg",
            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></span></div>",
            "extraMarkup": "<div class=\"absolute top-1/2 inset-e-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></div>"
        }'
        class="hidden">

        <option value=""></option>

        @if (!$isApi)
            @foreach ($normalizedOptions as $optValue => $optLabel)
                <option value="{{ $optValue }}" {{ (string) old($name, $value) === (string) $optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        @endif
    </select>

    @if ($clearable)
        <button type="button" data-clear-select="{{ $id }}" style="display: none;"
            class="absolute top-1/2 inset-e-9 -translate-y-1/2 z-20 cursor-pointer
                   text-(--color-dark-gray) hover:text-(--color-danger) transition">
            <i data-lucide="x" class="size-4"></i>
        </button>
    @endif
</div>
