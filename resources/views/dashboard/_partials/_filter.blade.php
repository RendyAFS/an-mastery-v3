<div class="flex flex-wrap items-end gap-3">
    <div>
        <label for="filter-date-range"
            class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('dashboard.filter.date_range') }}
        </label>
        <x-datepicker id="filter-date-range" name="date_range" mode="range"
            bgClass="bg-(--color-light) dark:bg-(--color-dark)" roundedClass="rounded-lg" />
    </div>

    <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw" text="{{ __('dashboard.filter.reset') }}"
        loadingText="{{ __('button-loading.Saving...') }}" color="bg-(--color-danger) hover:bg-(--color-danger)/80"
        textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]" rounded="rounded-lg"
        class="cursor-pointer" />
</div>
