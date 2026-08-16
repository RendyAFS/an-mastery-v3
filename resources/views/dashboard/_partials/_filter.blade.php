<div class="flex flex-wrap items-end gap-3">
    <div>
        <label for="filter-week-start" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('dashboard.filter.week_start') }}
        </label>
        <input type="week" id="filter-week-start"
            class="mt-1 px-4 py-2 block w-48 rounded-lg
                bg-(--color-light) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
    </div>

    <div>
        <label for="filter-week-end" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('dashboard.filter.week_end') }}
        </label>
        <input type="week" id="filter-week-end"
            class="mt-1 px-4 py-2 block w-48 rounded-lg
                bg-(--color-light) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
    </div>

    <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw" text="{{ __('dashboard.filter.reset') }}"
        loadingText="{{ __('button-loading.Saving...') }}" color="bg-(--color-danger) hover:bg-(--color-danger)/80"
        textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]" rounded="rounded-lg"
        class="cursor-pointer" />
</div>
