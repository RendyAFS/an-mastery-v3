@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => 'Choose data',
    'searchPlaceholder' => 'Search...',
    'id' => $name,
])

<div>
    @if ($label)
        <label for="{{ $id }}"
            class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light) mb-1">
            {{ $label }}
        </label>
    @endif

    <select name="{{ $name }}" id="{{ $id }}"
        data-hs-select='{
            "hasSearch": true,
            "searchPlaceholder": "{{ $searchPlaceholder }}",
            "placeholder": "{{ $placeholder }}",

            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2.5 ps-4 pe-9 flex w-full cursor-pointer bg-(--color-light-gray) border border-(--color-gray) rounded-lg text-start text-sm text-(--color-dark) focus:outline-hidden focus:ring-2 focus:ring-(--color-primary)/30 dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)",

            "dropdownClasses": "mt-2 max-h-60 p-1 z-50 w-full bg-(--color-light-gray) dark:bg-(--color-dark-slate) border border-(--color-gray) rounded-lg overflow-y-auto dark:border-(--color-slate)",

            "searchWrapperClasses": "sticky top-0 p-2 bg-(--color-light-gray) dark:bg-(--color-dark-slate)",

            "searchClasses": "px-4 py-2 block w-full text-sm rounded-md border border-(--color-gray) bg-(--color-light-gray) text-(--color-dark) placeholder:text-(--color-dark)/60 dark:placeholder:text-(--color-light)/60 focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30 dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)",

            "optionClasses": "py-2 px-4 w-full rounded-lg text-sm text-(--color-dark) cursor-pointer hover:bg-(--color-gray) dark:text-(--color-light) dark:hover:bg-(--color-slate)",

            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"size-3.5 text-gray-500 dark:text-gray-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
        }'
        class="hidden">
        <option></option>

        @foreach ($options as $key => $text)
            <option value="{{ $key }}" {{ (string) old($name, $value) === (string) $key ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
