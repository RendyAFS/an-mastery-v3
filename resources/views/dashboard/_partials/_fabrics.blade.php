<div>
    <p class="text-sm font-semibold mb-3">{{ __('dashboard.fabric_table.title') }}</p>

    <x-cardgrid id="dashboard-fabrics-cardgrid" :filter="false" :length="false" :defaultLength="6"
        gridCols="grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
    </x-cardgrid>
</div>
