<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <x-stat-card id="stat-employees" icon="users" color="primary" label="{{ __('dashboard.stats.employees') }}"
        value="0" />

    <x-stat-card id="stat-suppliers" icon="factory" color="info" label="{{ __('dashboard.stats.suppliers') }}"
        value="0" />

    <x-stat-card id="stat-bill-unpaid-count" sub-id="stat-bill-unpaid-total" icon="receipt" color="danger"
        label="{{ __('dashboard.stats.bill_unpaid') }}" value="0" sublabel="Rp 0" />

    <x-stat-card id="stat-salary-pending" icon="wallet" color="warning"
        label="{{ __('dashboard.stats.salary_pending') }}" value="0" />
</div>
