<div id="memo-form-fields" class="space-y-5">

    <div>
        <x-select id="employee_id" name="employee_id" label="{{ __('memo.fields.employee') }}"
            placeholder="{{ __('memo.placeholders.employee') }}"
            searchPlaceholder="{{ __('memo.placeholders.search_employee') }}" :apiUrl="route('employees.select')" apiDataPart="results"
            fieldId="id" fieldTitle="name" clearable="true" dropdown-scope="window" />
    </div>

    <div class="group relative">
        <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('memo.fields.item_name') }} <span class="text-(--color-red)">*</span>
        </label>
        <input type="text" id="name" name="name" placeholder="{{ __('memo.placeholders.item_name') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg
                bg-(--color-light-gray) border border-(--color-gray)
                text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">

        <div class="hidden group-focus-within:flex flex-wrap gap-1 mt-1">
            @foreach (__('memo.quick_items') as $quick)
                <button type="button" data-quick-name="{{ $quick }}" data-target="#name"
                    class="btn-quick-name px-2 py-0.5 text-[11px] rounded-md
                    bg-(--color-light-gray) border border-(--color-gray)
                    text-(--color-dark) hover:bg-(--color-gray)/40
                    dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                    cursor-pointer">
                    {{ $quick }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="nominal" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                {{ __('memo.fields.nominal') }} <span class="text-(--color-red)">*</span>
            </label>
            <div class="relative mt-1">
                <span
                    class="absolute inset-y-0 inset-s-0 flex items-center pl-4 text-sm text-(--color-dark-gray)">Rp</span>
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
</div>
