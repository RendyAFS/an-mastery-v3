@props([
    'name',
    'id' => $name,
    'options' => [],
    'value' => null,
    'allLabel' => null,
    'allValue' => '',
    'layout' => 'wrap',
    'multiple' => false,
])

@php
    $rawValue = is_array($value) ? implode(',', $value) : $value;
    $current = (string) old($name, $rawValue);
    $currentValues = $current === '' ? [] : explode(',', $current);

    $layoutClasses = match ($layout) {
        'scroll' => 'flex flex-nowrap gap-2 w-full overflow-x-auto pb-1 scrollbar-hide',
        'stack' => 'flex flex-col sm:inline-flex sm:flex-row sm:flex-wrap gap-2',
        default => 'inline-flex flex-wrap gap-2',
    };

    $itemLayoutClasses = $layout === 'scroll' ? 'shrink-0 whitespace-nowrap' : '';

    $isValueActive = function (string $val) use ($currentValues, $allValue) {
        if (empty($currentValues)) {
            return $val === (string) $allValue;
        }
        return in_array($val, $currentValues, true);
    };
@endphp

<div id="{{ $id }}-group" data-button-group data-target="{{ $id }}"
    data-multiple="{{ $multiple ? 'true' : 'false' }}" data-all-value="{{ $allValue }}" class="{{ $layoutClasses }}">
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $current }}">

    @if ($allLabel)
        <button type="button" data-value="{{ $allValue }}"
            data-active="{{ $isValueActive((string) $allValue) ? 'true' : 'false' }}"
            class="btn-group-item {{ $itemLayoutClasses }} py-2 px-4 rounded-lg border text-sm font-medium cursor-pointer transition
                {{ $isValueActive((string) $allValue)
                    ? 'bg-(--color-primary) border-(--color-primary) text-white'
                    : 'bg-(--color-light) dark:bg-(--color-dark) border-(--color-gray) dark:border-(--color-slate) text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-primary)/10' }}">
            {{ $allLabel }}
        </button>
    @endif

    @foreach ($options as $key => $label)
        <button type="button" data-value="{{ $key }}"
            data-active="{{ $isValueActive((string) $key) ? 'true' : 'false' }}"
            class="btn-group-item {{ $itemLayoutClasses }} py-2 px-4 rounded-lg border text-sm font-medium cursor-pointer transition
                {{ $isValueActive((string) $key)
                    ? 'bg-(--color-primary) border-(--color-primary) text-white'
                    : 'bg-(--color-light) dark:bg-(--color-dark) border-(--color-gray) dark:border-(--color-slate) text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-primary)/10' }}">
            {{ $label }}
        </button>
    @endforeach
</div>
