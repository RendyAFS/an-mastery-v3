import initDatatable from "@/utils/datatable";

const statusBadgeMap = {
    ON_PROGRESS: "badge-warning",
    DONE: "badge-info",
    DELIVERED: "badge-success",
    RETURNED: "badge-danger",
};

let datatable;

const renderInventory = (data) => {
    if (!data || !data.colors || data.colors.length === 0) {
        return `<span class="text-gray-400 text-sm">-</span>`;
    }

    const excessParts = data.colors
        .filter((c) => c.excess > 0)
        .map((c) => `+ ${c.excess} ${c.name}`)
        .join(" ");

    const seriText = excessParts
        ? `${data.total_pcs} pcs / ${data.seri} seri (${excessParts})`
        : `${data.total_pcs} pcs total / ${data.seri} seri`;

    const rows = data.colors
        .map((color) => {
            const dotColor = color.color || "#9ca3af";

            let badges = (color.statuses || [])
                .filter((s) => s.count > 0)
                .map((s) => {
                    const cls = statusBadgeMap[s.status] || "badge-primary";
                    return `<span class="badge ${cls}">${s.label}: ${s.count}</span>`;
                })
                .join("");

            if (!badges) {
                badges = `<span class="text-[11px] text-gray-400">${window.langFabric?.no_sablon_yet ?? ""}</span>`;
            }

            return `
                <div class="flex items-center justify-between px-3 py-1.5 gap-2">
                    <div class="flex items-center gap-2">
                        <span class="size-2.5 rounded-full shrink-0" style="background-color: ${dotColor}"></span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">${color.name ?? "-"}</span>
                        <span class="text-xs text-gray-400">(${color.stock} pcs)</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-1 justify-end">
                        ${badges}
                    </div>
                </div>
            `;
        })
        .join("");

    return `
        <div class="rounded-xl border border-(--color-gray)/20 dark:border-(--color-dark-gray)/30 overflow-hidden text-left">
            <div class="px-3 py-2.5 bg-(--color-gray)/10 dark:bg-(--color-dark-gray)/20">
                <div class="flex justify-between items-center gap-2 mb-1">
                    <div class="flex items-center gap-2">
                        <span class="flex items-center justify-center size-7 rounded-lg
                            bg-(--color-primary)/20 dark:bg-(--color-primary)/10 text-(--color-primary)">
                            <i data-lucide="layers" class="size-4"></i>
                        </span>
                        <div>
                            <div class="font-semibold text-sm">${seriText}</div>
                            <div class="text-xs text-(--color-dark-gray) dark:text-(--color-light-gray)">
                                ${data.type_fabric ?? "-"}
                            </div>
                        </div>
                    </div>
                    <div class="text-[11px] text-(--color-dark) dark:text-(--color-light) font-semibold">
                        ${window.langFabric?.incoming_label ?? "Incoming"} : ${data.date_coming ?? "-"}
                    </div>
                </div>
            </div>
            <div class="divide-y divide-(--color-gray)/10 dark:divide-(--color-dark-gray)/20">
                ${rows}
            </div>
        </div>
    `;
};

export const initFabricsTable = () => {
    datatable = initDatatable({
        table: "#dashboard-fabrics-datatable",
        pageLength: 5,
        rowClickRoute: (row) => route("fabrics.edit", row.id),
        ajax: {
            url: route("dashboard.fabrics"),
            method: "GET",
            dataSrc: "data",
        },
        columns: [
            { data: "supplier.name", width: "20%" },
            {
                data: "total_inventory_fabric",
                width: "50%",
                className: "dt-body-center",
                render: renderInventory,
            },
            {
                data: "notes",
                width: "30%",
                render(data) {
                    return `<div class="whitespace-pre-line">${data ?? "-"}</div>`;
                },
            },
        ],
    });
};

export const reloadFabricsTable = () => {
    datatable?.ajax.reload(null, false);
};
