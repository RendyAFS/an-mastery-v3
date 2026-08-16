@props([
    'name',
    'id' => $name,
    'label' => null,
    'mode' => 'single',
    'value' => null,
    'startValue' => null,
    'endValue' => null,
    'placeholder' => null,
    'dateFormat' => 'Y-m-d',
    'altFormat' => 'd F Y',
    'enableTime' => false,
    'minDate' => null,
    'maxDate' => null,
    'clearable' => false,
    'wrapperClass' => '',
    'inputClass' => 'min-w-[150px]',
    'bgClass' => 'bg-(--color-light-gray) dark:bg-(--color-dark-slate)',
    'roundedClass' => 'rounded-md',
    'icon' => 'calendar',
])

@php
    $config = array_filter(
        [
            'mode' => $mode,
            'dateFormat' => $dateFormat,
            'altInput' => true,
            'altFormat' => $altFormat,
            'enableTime' => $enableTime,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
        ],
        fn($v) => !is_null($v),
    );
    $placeholder = $placeholder ?? __('datepicker.placeholder.' . $mode);
@endphp

<div class="{{ $wrapperClass }}">
    @if ($label)
        <label for="{{ $id }}"
            class="mb-2 block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute top-1/2 inset-s-3 -translate-y-1/2 text-(--color-dark-gray)">
                <i data-lucide="{{ $icon }}" class="size-4"></i>
            </span>
        @endif

        <input type="text" id="{{ $id }}" data-flatpickr='@json($config)'
            placeholder="{{ $placeholder }}" value="{{ $mode === 'single' ? $value : '' }}" autocomplete="off"
            class="mt-1 py-2 block w-full {{ $inputClass }} {{ $bgClass }} border border-(--color-gray) {{ $roundedClass }}
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:border-(--color-slate) dark:text-(--color-light)
                {{ $icon ? 'ps-10' : 'px-4' }} {{ $clearable ? 'pe-9' : 'pe-4' }}">

        @if ($mode === 'single')
            <input type="hidden" name="{{ $name }}" id="{{ $id }}_value"
                value="{{ $value }}">
        @else
            <input type="hidden" name="{{ $name }}_start" id="{{ $id }}_start"
                value="{{ $startValue }}">
            <input type="hidden" name="{{ $name }}_end" id="{{ $id }}_end"
                value="{{ $endValue }}">
        @endif

        @if ($clearable)
            <button type="button" data-flatpickr-clear="{{ $id }}"
                class="absolute top-1/2 inset-e-3 -translate-y-1/2 z-20 cursor-pointer
                    text-(--color-dark-gray) hover:text-(--color-danger) transition">
                <i data-lucide="x" class="size-4"></i>
            </button>
        @endif
    </div>
</div>
