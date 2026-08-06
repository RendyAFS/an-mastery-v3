<div class="grid grid-cols-1 gap-4">
    <div class="col-span-1">
        <div class="mb-2 space-y-2">
            <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('employee.fields.name') }}
            </label>

            <input type="text" id="name" name="name" value="{{ $employee->name ?? '' }}"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>
        <div class="mb-2 space-y-2">
            <label for="contact" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('employee.fields.contact') }}
            </label>

            <input type="text" id="contact" name="contact" value="{{ $employee->contact ?? '' }}"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-2 space-y-2">
            <label for="address" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('employee.fields.address') }}
            </label>

            <textarea id="address" name="address" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $employee->address ?? '' }}</textarea>
        </div>

        <div class="mb-2 space-y-2">
            <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('employee.fields.notes') }}
            </label>

            <textarea id="notes" name="notes" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $employee->notes ?? '' }}</textarea>
        </div>
    </div>
</div>
