const formatDateRange = (start, end) => {
    if (!start || !end) return "";
    const opts = { day: "numeric", month: "long", year: "numeric" };
    const startDate = new Date(start).toLocaleDateString("id-ID", opts);
    const endDate = new Date(end).toLocaleDateString("id-ID", opts);
    return `${startDate} - ${endDate}`;
};

export const renderPresences = (data) => {
    const container = $("#presence-list");
    const rangeEl = $("#presence-week-range");
    if (!container.length) return;

    const { week_start, week_end, employees } = data || {};
    const dayLabels = window.langDashboard?.presence?.days ?? {};

    rangeEl.text(formatDateRange(week_start, week_end));

    if (!employees || employees.length === 0) {
        container.html(
            `<p class="text-sm text-(--color-dark-gray)">${window.langDashboard?.presence?.no_presence ?? "-"}</p>`,
        );
        return;
    }

    const formatRupiah = (value) =>
        "Rp" + Number(value || 0).toLocaleString("id-ID");

    const rows = employees
        .map((p) => {
            const daysLabel = p.days?.length
                ? p.days.map((d) => dayLabels[d] ?? d).join(", ")
                : "-";

            return `
                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-(--color-gray)/10">
                    <div>
                        <p class="text-sm">${p.name}</p>
                        <p class="text-xs text-(--color-dark-gray)">${daysLabel}</p>
                    </div>
                    <span class="text-sm font-semibold">${formatRupiah(p.total)}</span>
                </div>
            `;
        })
        .join("");

    container.html(rows);
};
