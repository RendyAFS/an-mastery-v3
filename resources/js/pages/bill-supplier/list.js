import initCardgrid from "@/utils/cardgrid";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;

        return `
        <div class="h-full bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3 cursor-pointer
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate">${item.name}</p>
                    ${item.contact ? `<p class="text-xs text-(--color-gray) mt-0.5 truncate">${item.contact}</p>` : ""}
                </div>

                ${
                    isDeleted
                        ? `<span class="text-[10px] px-2 py-1 rounded-full bg-(--color-red)/10 text-(--color-red) flex items-center gap-1 shrink-0"><i data-lucide="trash-2" class="size-3"></i> Deleted</span>`
                        : !item.is_active
                          ? `<span class="text-[10px] px-2 py-1 rounded-full bg-(--color-gray)/20 text-(--color-gray) shrink-0">Inactive</span>`
                          : ""
                }
            </div>

            ${item.address ? `<p class="text-xs text-(--color-gray) line-clamp-2">${item.address}</p>` : ""}

            <div class="mt-auto pt-3 border-t border-(--color-gray)/20 space-y-1.5">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-(--color-gray)">Belum Ditagih</span>
                    <span class="font-medium">${item.unbilled_sablons_count} sablon</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-(--color-gray)">Bill Belum Lunas</span>
                    <span class="font-medium text-(--color-red)">${item.unpaid_bills_count}</span>
                </div>
                <div class="flex items-center justify-between text-sm pt-1">
                    <span class="text-(--color-gray)">Total Tagihan</span>
                    <span class="font-bold">${formatCurrency(item.total_unpaid)}</span>
                </div>
            </div>
        </div>`;
    };

    return {
        init() {
            initCardgrid({
                containerId: "#bill-supplier-cardgrid",
                ajax: {
                    url: route("bill_suppliers.index"),
                },
                renderCard,
                pageLength: 12,
                cardClickRoute: (row) =>
                    route("bill_suppliers.by-supplier", row.id),
            });
        },
    };
})();

$(function () {
    PageScript.init();
});
