<div class="grid grid-cols-1 gap-4">
    <div class="col-span-1">
        <div class="mb-2 space-y-2">
            <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('type-color.fields.name') }}
            </label>

            <input type="number" id="name" name="name" value="{{ $typeColor->name ?? '' }}" required
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-2 space-y-2">
            <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('type-color.fields.notes') }}
            </label>

            <textarea id="notes" name="notes" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $typeColor->notes ?? '' }}</textarea>
        </div>
    </div>
</div>
