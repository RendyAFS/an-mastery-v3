<div>
    <p class="text-sm font-semibold mb-3">{{ __('dashboard.fabric_table.title') }}</p>

    <x-datatable id="dashboard-fabrics-datatable" :filter="false" :length="false">
        <thead class="border-b">
            <tr>
                <th
                    class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                    {{ __('fabric.fields.supplier') }}
                </th>
                <th
                    class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                    <div class="flex justify-center items-center w-full">
                        {{ __('fabric.fields.stock_total') }}
                    </div>
                </th>
                <th
                    class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                    {{ __('fabric.fields.notes') }}
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
    </x-datatable>
</div>
