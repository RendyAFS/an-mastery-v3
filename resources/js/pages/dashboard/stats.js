export const renderStats = (stats) => {
    if (!stats) return;

    $("#stat-employees").text(stats.employees_total ?? 0);
    $("#stat-suppliers").text(stats.suppliers_total ?? 0);
    $("#stat-bill-unpaid-count").text(stats.bill_supplier_unpaid_count ?? 0);
    $("#stat-bill-unpaid-total").text(
        stats.bill_supplier_unpaid_total_formatted ?? "Rp 0",
    );
    $("#stat-salary-pending").text(stats.salary_pending_count ?? 0);
};
