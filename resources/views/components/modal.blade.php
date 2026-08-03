@props(['id', 'title' => null, 'size' => 'md'])

@php
    $sizeMap = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ];
    $maxWidth = $sizeMap[$size] ?? $sizeMap['md'];
@endphp

<div id="{{ $id }}"
    class="hs-overlay hidden size-full fixed top-0 inset-s-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
    role="dialog" tabindex="-1" aria-labelledby="{{ $id }}-label">

    <div
        class="hs-overlay-animation-target hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500
        mt-0 opacity-0 ease-out transition-all {{ $maxWidth }} sm:w-full m-3 sm:mx-auto">

        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl pointer-events-auto
            dark:bg-(--color-dark) dark:border-(--color-slate)">

            @if ($title)
                <div
                    class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                    <h3 id="{{ $id }}-label"
                        class="font-semibold text-(--color-dark) dark:text-(--color-light)">
                        {{ $title }}
                    </h3>
                    <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full
                            bg-(--color-light-gray) border border-(--color-gray)
                            text-(--color-dark) hover:bg-(--color-gray)/40
                            dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                            focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                        aria-label="Close" data-hs-overlay="#{{ $id }}">
                        <span class="sr-only">Close</span>
                        <i data-lucide="x" class="text-(--color-dark)/80 dark:text-(--color-light)/80 size-5"></i>
                    </button>
                </div>
            @endif

            <div class="p-4 overflow-y-auto space-y-4">
                {{ $slot }}
            </div>

            @isset($footer)
                <div class="flex gap-2 p-4 border-t border-(--color-gray)/20">
                    {{ $footer }}
                </div>
            @endisset

        </div>
    </div>
</div>
