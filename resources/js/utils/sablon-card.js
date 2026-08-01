import RupiahInput from "@/utils/rupiah-input";

export const statusBadgeMap = {
    ON_PROGRESS: "badge-warning",
    DONE: "badge-info",
    DELIVERED: "badge-success",
    RETURNED: "badge-danger",
};

export const sablonHeaderHtml = (item) => `
    <div class="flex items-start justify-between gap-2">
        <div class="min-w-0">
            <p class="font-bold text-sm truncate">
                ${item.image_fabric ?? "-"} | ${item.type_fabric ?? "-"}
            </p>
            <p class="text-xs text-(--color-dark-gray)">${item.date_sablon ?? "-"}</p>
        </div>
        <span class="badge ${statusBadgeMap[item.status] ?? "badge-primary"} shrink-0">
            ${item.status_label ?? (item.status ? item.status.replaceAll("_", " ") : "-")}
        </span>
    </div>`;

export const sablonSummaryHtml = (item) => `
    <div class="grid grid-cols-2 gap-2">
        <div class="bg-(--color-gray)/10 rounded-lg p-2">
            <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Long Fabric</p>
            <p class="text-sm font-medium">${item.total_long_fabric ?? 0} m</p>
        </div>
        <div class="bg-(--color-gray)/10 rounded-lg p-2">
            <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Type Color</p>
            <p class="text-sm font-medium">${item.type_color ?? "-"} Warna</p>
        </div>
        <div class="bg-(--color-gray)/10 rounded-lg p-2 col-span-2">
            <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Total Fee</p>
            <p class="text-sm font-semibold text-(--color-primary)">Rp${RupiahInput.format(item.total_fee ?? 0)}</p>
        </div>
    </div>`;

export const fabricDetailsHtml = (item) => {
    const details = item.fabric_details ?? [];
    if (!details.length) return "";

    return `
    <div class="space-y-1">
        <p class="text-xs font-semibold">Fabric Details</p>
        <ul class="space-y-1 text-xs">
            ${details
                .map(
                    (d) => `
                    <li class="flex justify-between">
                        <span>• ${d.color_fabric ?? "-"}</span>
                        <span class="font-medium">${d.long_fabric ?? 0} m</span>
                    </li>`,
                )
                .join("")}
        </ul>
    </div>`;
};
