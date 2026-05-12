<div class="grid grid-cols-1 gap-4">
    <div class="col-span-1">
        <div class="mb-6 space-y-2">
            <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Name
            </label>
            <input type="text" id="name" name="name" value="{{ $colorFabric->name ?? '' }}" required
                class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-6 space-y-2">
            <label for="color_picker"
                class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Code Color
            </label>

            <div class="flex items-center gap-3">
                <!-- Color Picker -->
                <input type="color"
                    id="color_picker"
                    value="{{ $colorFabric->code_color ?? '#000000' }}"
                    class="p-1 h-11 w-16 block rounded-lg cursor-pointer
                        bg-(--color-light-gray)
                        border border-(--color-gray)
                        dark:bg-(--color-dark-slate)
                        dark:border-(--color-slate)">

                <!-- Hex Code -->
                <input type="text"
                    id="code_color"
                    name="code_color"
                    value="{{ $colorFabric->code_color ?? '#000000' }}"
                    readonly
                    required
                    class="flex-1 px-4 py-2 block rounded-lg
                        bg-(--color-light-gray)
                        border border-(--color-gray)
                        text-(--color-dark)
                        dark:bg-(--color-dark-slate)
                        dark:border-(--color-slate)
                        dark:text-(--color-light)">
            </div>
        </div>

        <div class="mb-4 space-y-2">
            <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Notes
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $colorFabric->notes ?? '' }}</textarea>
        </div>
    </div>
</div>
