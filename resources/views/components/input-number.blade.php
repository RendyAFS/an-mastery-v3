@props(['model', 'min' => 0, 'step' => 1])

<div
    class="py-2 px-3 rounded-lg border border-(--color-gray)
    bg-(--color-light-gray)
    dark:bg-(--color-dark-slate)
    dark:border-(--color-slate)
    focus-within:border-(--color-primary)
    focus-within:ring-2
    focus-within:ring-(--color-primary)/20
    transition-all duration-150">

    <div class="flex items-center justify-between gap-x-3">

        <div class="grow">

            <input type="number" min="{{ $min }}" step="{{ $step }}" x-model="{{ $model }}"
                @focus="if(Number({{ $model }}) === {{ $min }}) {$event.target.select();}"
                @blur="{{ $model }} = NumberInput.normalize({{ $model }}, {{ $min }});"
                class="w-full p-0 bg-transparent border-0
                    text-(--color-dark)
                    dark:text-(--color-light)
                    placeholder:text-(--color-gray)
                    focus:outline-none
                    focus:ring-0
                    [&::-webkit-inner-spin-button]:appearance-none
                    [&::-webkit-outer-spin-button]:appearance-none"
                style="-moz-appearance:textfield;">
        </div>

        <div class="flex items-center gap-x-1">

            <button type="button" @mousedown.prevent
                @click="{{ $model }} = NumberInput.decrement({{ $model }}, {{ $min }}, {{ $step }})"
                :disabled="Number({{ $model }} || {{ $min }}) <= {{ $min }}"
                class="size-7 inline-flex items-center justify-center rounded-full
                border border-(--color-gray)
                bg-(--color-light)
                hover:bg-(--color-gray)/20
                active:scale-95
                transition-all duration-150
                disabled:opacity-40
                disabled:pointer-events-none
                dark:bg-(--color-dark)
                dark:border-(--color-slate)
                dark:hover:bg-(--color-dark-gray)/30">

                <i data-lucide="minus" class="size-3.5">
                </i>

            </button>

            <button type="button" @mousedown.prevent
                @click="{{ $model }} = NumberInput.increment({{ $model }}, {{ $step }})"
                class="size-7 inline-flex items-center justify-center rounded-full
                border border-(--color-gray)
                bg-(--color-light)
                hover:bg-(--color-gray)/20
                active:scale-95
                transition-all duration-150
                dark:bg-(--color-dark)
                dark:border-(--color-slate)
                dark:hover:bg-(--color-dark-gray)/30">

                <i data-lucide="plus" class="size-3.5">
                </i>
            </button>
        </div>
    </div>
</div>
