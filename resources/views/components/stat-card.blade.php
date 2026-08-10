@props([
    'id' => null,
    'icon' => 'circle',
    'label' => '',
    'value' => '0',
    'sublabel' => null,
    'subId' => null,
    'color' => 'primary',
])

@php
    $colorMap = [
        'primary' => [
            'bg' => 'bg-(--color-primary)/15',
            'text' => 'text-(--color-primary)',
            'ring' => 'from-(--color-primary)/10',
        ],
        'success' => [
            'bg' => 'bg-(--color-success)/15',
            'text' => 'text-(--color-success)',
            'ring' => 'from-(--color-success)/10',
        ],
        'warning' => [
            'bg' => 'bg-(--color-warning)/15',
            'text' => 'text-(--color-warning)',
            'ring' => 'from-(--color-warning)/10',
        ],
        'danger' => [
            'bg' => 'bg-(--color-danger)/15',
            'text' => 'text-(--color-danger)',
            'ring' => 'from-(--color-danger)/10',
        ],
        'info' => ['bg' => 'bg-(--color-info)/15', 'text' => 'text-(--color-info)', 'ring' => 'from-(--color-info)/10'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div
    class="relative overflow-hidden bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4
    border border-(--color-gray)/10 dark:border-(--color-dark-gray)/20">

    <div class="absolute -right-6 -top-6 size-24 rounded-full bg-gradient-to-br {{ $c['ring'] }} to-transparent">
    </div>

    <div class="relative flex items-start justify-between">
        <div class="min-w-0">
            <p class="text-xs font-medium text-(--color-dark-gray) truncate">{{ $label }}</p>
            <p id="{{ $id }}" class="text-2xl font-bold mt-1 text-(--color-dark) dark:text-(--color-light)">
                {{ $value }}</p>

            @if ($sublabel)
                <p id="{{ $subId }}" class="text-xs font-medium {{ $c['text'] }} mt-1">{{ $sublabel }}</p>
            @endif
        </div>

        <span
            class="shrink-0 flex items-center justify-center size-11 rounded-xl {{ $c['bg'] }} {{ $c['text'] }}">
            <i data-lucide="{{ $icon }}" class="size-5"></i>
        </span>
    </div>
</div>
