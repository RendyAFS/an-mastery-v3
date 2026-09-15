<div class="grid grid-cols-2 gap-3">
    <div class="col-span-1">
        <label for="min" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('bonus.fields.min') }}
        </label>
        <input type="text" inputmode="numeric" id="min" name="min"
            value="{{ $bonus->min ?? '' }}" data-rupiah required
            placeholder="{{ __('bonus.placeholders.min') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="col-span-1">
        <label for="bonus" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('bonus.fields.bonus') }}
        </label>
        <input type="text" inputmode="numeric" id="bonus" name="bonus"
            value="{{ $bonus->bonus ?? '' }}" data-rupiah required
            placeholder="{{ __('bonus.placeholders.bonus') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="col-span-full">
        <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('bonus.fields.notes') }}
        </label>

        <textarea id="notes" name="notes" rows="3"
            placeholder="{{ __('bonus.placeholders.notes') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $bonus->notes ?? '' }}</textarea>
    </div>
</div>
