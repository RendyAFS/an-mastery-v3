@props([
    'name',
    'label' => null,
    'id' => $name,
    'placeholder' => 'Select date',
    'value' => null, // string for single/week, ['start' => .., 'end' => ..] for range, array for multiple
    'mode' => 'single', // single | range | week | multiple
    'dateFormat' => 'dd/MM/yyyy',
    'altFieldDateFormat' => 'yyyy-MM-dd', // ISO format stored in the hidden input(s), matches Carbon::parse()
    'minDate' => null,
    'maxDate' => null,
    'timepicker' => false,
    'timeFormat' => null,
    'inline' => false,
    'clearable' => false,
    'disabled' => false,
    'autoClose' => true,
    'position' => 'bottom left',
    'locale' => 'en', // en | id
    'firstDay' => 1, // 1 = Monday
    'container' => null, // css selector to scope the dropdown, useful inside modals
    'multipleDatesSeparator' => ', ',
    'buttons' => null, // e.g. ['clear', 'today']
    'wrapperClass' => '',
    'inputClass' => '',
])

@php
    $isRange = $mode === 'range';
    $isWeek = $mode === 'week';
    $isMultiple = $mode === 'multiple';

    // range/week both keep the boundaries of a period, everything else is a single logical field
    $startName = $name;
    $endName = $isRange ? $name . '_end' : null;

    if ($isRange) {
        $startName = $name . '_start';
    }

    $startValue = is_array($value) ? $value['start'] ?? ($value[0] ?? null) : $value;
    $endValue = is_array($value) ? $value['end'] ?? ($value[1] ?? null) : null;

    if ($isMultiple && is_array($value)) {
        $startValue = implode(',', $value);
    }
@endphp

<div class="{{ $wrapperClass }}" data-datepicker-wrapper>
    @if ($label)
        <div class="flex justify-between items-center">
            <label for="{{ $id }}"
                class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ $label }}
            </label>

            @if ($clearable)
                <button type="button" data-clear-datepicker="{{ $id }}" style="display: none;"
                    class="text-sm font-semibold transition ease-in-out
                           text-(--color-danger) cursor-pointer
                           hover:text-(--color-danger)/70">
                    Clear
                </button>
            @endif
        </div>
    @endif

    {{-- Visible input the calendar is bound to. It never carries a `name`
         so it never gets submitted -- the hidden input(s) below do that. --}}
    <input type="text" id="{{ $id }}" placeholder="{{ $placeholder }}" autocomplete="off"
        {{ $disabled ? 'disabled' : '' }} data-air-datepicker data-mode="{{ $mode }}"
        data-start-field="{{ $startName }}"
        @if ($endName) data-end-field="{{ $endName }}" @endif
        data-options='@js([
            'dateFormat' => $dateFormat,
            'altFieldDateFormat' => $altFieldDateFormat,
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'timepicker' => $timepicker,
            'timeFormat' => $timeFormat,
            'inline' => $inline,
            'autoClose' => $autoClose,
            'position' => $position,
            'locale' => $locale,
            'firstDay' => $firstDay,
            'container' => $container,
            'multipleDatesSeparator' => $multipleDatesSeparator,
            'buttons' => $buttons,
        ])'
        {{ $attributes->merge([
            'class' =>
                $inputClass ?:
                'px-4 py-2.5 block w-full rounded-lg bg-(--color-light) border border-(--color-gray) text-sm text-(--color-dark) placeholder:text-(--color-dark)/60 focus:outline-hidden focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30 disabled:opacity-50 disabled:pointer-events-none dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light) dark:placeholder:text-(--color-light)/60',
        ]) }} />

    <input type="hidden" name="{{ $startName }}" id="{{ $startName }}-hidden"
        value="{{ old($startName, $startValue) }}" data-datepicker-hidden="start" />

    @if ($endName)
        <input type="hidden" name="{{ $endName }}" id="{{ $endName }}-hidden"
            value="{{ old($endName, $endValue) }}" data-datepicker-hidden="end" />
    @endif
</div>
