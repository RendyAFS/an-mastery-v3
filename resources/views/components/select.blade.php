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
@endphp

<div class="mb-6 space-y-2">
    @if ($label)
        <div class="flex justify-between items-center">
            <label for="{{ $id }}"
                class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ $label }}
            </label>

            @if ($clearable)
                <button type="button" data-clear-select="{{ $id }}" style="display: none;"
                    class="text-sm font-semibold transition ease-in-out
                       text-(--color-danger) cursor-pointer
                       hover:text-(--color-danger)/70">
                    Clear
                </button>
            @endif
        </div>
    @endif

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
                "apiSearchQueryKey": "{{ $searchQueryKey }}",
                {{ $dropdownScope ? '"dropdownScope": "' . $dropdownScope . '",' : '' }} @endif
            "hasSearch": true,
            "searchPlaceholder": "{{ $searchPlaceholder }}",
            "placeholder": "{{ $placeholder }}",
            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2.5 ps-4 pe-9 flex w-full cursor-pointer bg-(--color-light-gray) border border-(--color-gray) rounded-lg text-start text-sm text-(--color-dark) focus:outline-hidden focus:ring-2 focus:ring-(--color-primary)/30 dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)",
            "dropdownClasses": "mt-2 max-h-60 p-1 z-{{ $isApi ? '80' : '50' }} w-full bg-(--color-light-gray) dark:bg-(--color-dark-slate) border border-(--color-gray) rounded-lg overflow-y-auto dark:border-(--color-slate)",
            "searchWrapperClasses": "sticky top-0 p-2 bg-(--color-light-gray) dark:bg-(--color-dark-slate)",
            "searchClasses": "px-4 py-2 block w-full text-sm rounded-md border border-(--color-gray) bg-(--color-light-gray) text-(--color-dark) placeholder:text-(--color-dark)/60 dark:placeholder:text-(--color-light)/60 focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30 dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)",
            "optionClasses": "py-2 px-4 w-full text-sm text-select-item-foreground cursor-pointer hover:bg-select-item-hover rounded-lg focus:outline-hidden focus:bg-select-item-focus",
            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><i data-lucide=\"check\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></span></div>",
            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><i data-lucide=\"chevrons-up-down\" class=\"size-4 text-(--color-dark) dark:text-(--color-light)\"></i></div>"
        }'
        class="hidden">

        <option value=""></option>

        @if (!$isApi)
            @foreach ($options as $key => $text)
                <option value="{{ $key }}"
                    {{ (string) old($name, $value) === (string) $key ? 'selected' : '' }}>
                    {{ $text }}
                </option>
            @endforeach
        @endif
    </select>
</div>
