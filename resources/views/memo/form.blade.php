<div id="memo-form-fields" class="space-y-5">

    <div>
        <x-select id="employee_id" name="employee_id" label="{{ __('memo.fields.employee') }}"
            placeholder="{{ __('memo.placeholders.employee') }}"
            searchPlaceholder="{{ __('memo.placeholders.search_employee') }}" :apiUrl="route('employees.select')" apiDataPart="results"
            fieldId="id" fieldTitle="name" clearable="true" dropdown-scope="window" />
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('memo.fields.item_name') }} <span class="text-(--color-red)">*</span>
        </label>
        <input type="text" id="name" name="name" placeholder="{{ __('memo.placeholders.item_name') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg
                bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="nominal" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('memo.fields.nominal') }} <span class="text-(--color-red)">*</span>
            </label>
            <div class="relative mt-1">
                <span
                    class="absolute inset-y-0 start-0 flex items-center pl-4 text-sm text-(--color-dark-gray)">Rp</span>
                <input type="text" inputmode="numeric" id="nominal" name="nominal" placeholder="0"
                    class="pl-9 pr-4 py-2 block w-full rounded-lg
                        bg-(--color-light-gray) border border-(--color-gray)
                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                        dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>
        </div>

        <div>
            <label for="date" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('memo.fields.date') }} <span class="text-(--color-red)">*</span>
            </label>
            <input type="date" id="date" name="date"
                class="mt-1 px-4 py-2 block w-full rounded-lg
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>
    </div>

    <div class="flex items-center gap-3 pt-1">
        <label class="relative inline-block w-11 h-6 cursor-pointer">
            <input type="checkbox" id="is_paid" name="is_paid" value="1" class="peer sr-only">
            <span
                class="absolute inset-0 bg-(--color-dark-gray) rounded-full transition-colors duration-200
                ease-in-out peer-checked:bg-(--color-success) peer-disabled:opacity-50"></span>
            <span
                class="absolute top-1/2 inset-s-0.5 -translate-y-1/2 size-5 bg-(--color-light) rounded-full
                shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-full"></span>
        </label>
        <label for="is_paid" class="text-sm font-medium text-(--color-dark) dark:text-(--color-light) cursor-pointer">
            {{ __('memo.fields.is_paid') }}
        </label>
    </div>
</div>
