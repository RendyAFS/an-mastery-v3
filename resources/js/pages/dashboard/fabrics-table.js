import initCardgrid from "@/utils/cardgrid";

let cardgrid;

const renderCard = (item) => {
    const isDeleted = item.deleted_at !== null;
    const data = item.total_inventory_fabric || {};

    const statusBadgeMap = {
        ON_PROGRESS: "badge-warning",
        DONE: "badge-info",
        DELIVERED: "badge-success",
        RETURNED: "badge-danger",
    };

    const excessParts = (data.colors || [])
        .filter((c) => c.excess > 0)
        .map((c) => `+ ${c.excess} ${c.name}`)
        .join(" ");

    const seriText = excessParts
        ? `<span class="font-semibold">${data.total_pcs ?? 0} pcs</span> / <strong class="font-bold text-(--color-primary)">${data.seri ?? 0} seri</strong> (${excessParts})`
        : `<span class="font-semibold">${data.total_pcs ?? 0} pcs</span> total / <strong class="font-bold text-(--color-primary) dark:text-(--color-light-primary)">${data.seri ?? 0} seri</strong>`;

    let rows = (data.colors || [])
        .map((color) => {
            const dotColor = color.color || "#9ca3af";

            let badges = (color.statuses || [])
                .filter((s) => s.count > 0)
                .map((s) => {
                    const cls =
                        statusBadgeMap[s.status] || "badge-primary";
                    return `
                    <span class="badge ${cls} px-2! py-0.5! text-xs font-semibold rounded-md whitespace-nowrap">
                        ${s.label}: <strong class="font-bold ml-0.5">${s.count}</strong>
                    </span>
                `;
                })
                .join("");

            if (!badges) {
                badges = `<span class="text-[11px] text-gray-400 dark:text-gray-500 italic">${window.langFabric?.no_sablon_yet ?? ""}</span>`;
            }

            return `
            <div class="px-3 py-2 space-y-1.5 hover:bg-(--color-gray)/5 dark:hover:bg-(--color-dark-gray)/10 transition">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="size-2.5 rounded-full shrink-0 ring-1 ring-black/10 dark:ring-white/20" style="background-color: ${dotColor}"></span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">${color.name ?? "-"}</span>
                    </div>
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-300 bg-(--color-gray)/15 dark:bg-(--color-dark-gray)/30 px-2 py-0.5 rounded-md shrink-0">
                        ${color.stock} pcs
                    </span>
                </div>

                <div class="flex flex-wrap items-center gap-1.5">
                    ${badges}
                </div>
            </div>
        `;
        })
        .join("");

    return `
        <div class="relative bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col justify-between gap-3 cursor-pointer ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">
            <div class="space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-(--color-dark) dark:text-(--color-light)">
                            ${item.supplier?.name ?? "-"}
                        </h3>
                        <p class="text-xs text-(--color-dark-gray) dark:text-(--color-light-gray)">
                            ${item.type_fabric?.name ?? data.type_fabric ?? "-"}
                        </p>
                    </div>

                    <div class="text-[11px] font-medium text-(--color-dark-gray) dark:text-(--color-light-gray) shrink-0">
                        ${window.langFabric?.incoming_label ?? "Incoming"}: ${item.date_coming ?? data.date_coming ?? "-"}
                    </div>
                </div>

                <div class="rounded-xl border border-(--color-gray)/20 dark:border-(--color-dark-gray)/30 overflow-hidden text-left">
                    <div class="px-3 py-2 bg-(--color-gray)/10 dark:bg-(--color-dark-gray)/20 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="flex items-center justify-center size-7 rounded-lg
                                bg-(--color-primary)/20
                                dark:bg-(--color-primary)/10
                                text-(--color-primary)">
                                <i data-lucide="layers" class="size-4"></i>
                            </span>

                            <div class="font-semibold text-sm text-(--color-dark) dark:text-(--color-light)">
                                ${seriText}
                            </div>
                        </div>
                    </div>

                    <div class="divide-y divide-(--color-gray)/10 dark:divide-(--color-dark-gray)/20">
                        ${rows || `<div class="p-3 text-xs text-gray-400 text-center">-</div>`}
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                <span class="text-xs text-(--color-dark-gray)">
                    ${item.created_at ?? ""}
                </span>

                <div class="flex items-center gap-1">
                    <a href="${route("fabrics.edit", item.id)}"
                        class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                        <i data-lucide="square-pen" class="size-4"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
};

export const initFabricsTable = () => {
    cardgrid = initCardgrid({
        containerId: "#dashboard-fabrics-cardgrid",
        ajax: {
            url: route("dashboard.fabrics"),
        },
        renderCard,
        pageLength: 6,
        cardClickRoute: (row) => route("fabrics.edit", row.id),
    });
};

export const reloadFabricsTable = () => {
    cardgrid?.reload();
};
