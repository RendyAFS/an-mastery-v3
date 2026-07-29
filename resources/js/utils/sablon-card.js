import RupiahInput from "@/utils/rupiah-input";

export const statusColor = {
    ON_PROGRESS: "bg-yellow-500/10 text-yellow-600",
    DONE: "bg-blue-500/10 text-blue-600",
    DELIVERED: "bg-green-500/10 text-green-600",
    RETURNED: "bg-red-500/10 text-red-600",
};

export const sablonHeaderHtml = (item) => `
    <div class="flex items-start justify-between gap-2">
        <div class="min-w-0">
            <p class="font-bold text-sm truncate">
                ${item.image_fabric ?? "-"} | ${item.type_fabric ?? "-"}
            </p>
            <p class="text-xs text-(--color-dark-gray)">${item.date_sablon ?? "-"}</p>
        </div>
        <span class="shrink-0 text-[11px] px-2 py-0.5 rounded-full font-medium ${statusColor[item.status] ?? "bg-gray-500/10 text-gray-600"}">
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
