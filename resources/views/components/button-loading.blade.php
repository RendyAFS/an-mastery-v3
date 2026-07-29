@props([
    'type' => 'button',
    'text' => 'Submit',
    'loadingText' => 'Loading...',
    'color' => 'bg-blue-600 hover:bg-blue-700',
    'textColor' => 'text-white',
    'size' => 'py-2.5 px-4 text-sm',
    'rounded' => 'rounded-lg',
    'icon' => null,
])

<button type="{{ $type }}" data-button-loading data-loading-text="{{ $loadingText }}"
    {{ $attributes->merge([
        'class' => "
                    inline-flex items-center justify-center gap-x-2
                    font-medium transition-all duration-200
                    disabled:opacity-50 disabled:pointer-events-none
                    $size $rounded $color $textColor
                ",
    ]) }}>
    <span class="hidden animate-spin size-4 border-2 border-current border-t-transparent rounded-full" data-spinner
        aria-label="loading" role="status"></span>

    @if ($icon)
        <i data-lucide="{{ $icon }}" class="size-4" data-icon></i>
    @endif

    <span data-text>{{ $text }}</span>
</button>
