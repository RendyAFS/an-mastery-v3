<div class="lg:col-span-2 flex flex-col h-170">
    <p class="text-sm font-semibold mb-3 shrink-0">{{ __('dashboard.table.title') }}</p>

    <div class="flex-1 min-h-0 flex flex-col">
        <x-datatable id="dashboard-sablons-datatable" :filter="false" :length="false">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-4 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('dashboard.table.supplier') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-4 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('dashboard.table.image_fabric') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-4 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('dashboard.table.date') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-4 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        {{ __('dashboard.table.status') }}
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-4 py-3 text-xs font-medium text-muted-foreground-1 uppercase text-right">
                        {{ __('dashboard.table.total') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>
</div>
