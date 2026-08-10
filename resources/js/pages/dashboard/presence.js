export const renderPresences = (presences) => {
    const container = $("#presence-list");
    if (!container.length) return;

    if (!presences || presences.length === 0) {
        container.html(
            `<p class="text-sm text-(--color-dark-gray)">${window.langDashboard?.presence?.no_presence ?? "-"}</p>`,
        );
        return;
    }

    const formatRupiah = (value) =>
        "Rp" + Number(value || 0).toLocaleString("id-ID");

    const rows = presences
        .map(
            (p) => `
                <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-(--color-gray)/10">
                    <span class="text-sm">${p.name}</span>
                    <span class="text-sm font-semibold">${formatRupiah(p.total)}</span>
                </div>
            `,
        )
        .join("");

    container.html(rows);
};
