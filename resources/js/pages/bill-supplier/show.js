import ApiProvider from "@/utils/api-provider";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    let supplierId;

    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;

    const batchItemRow = (item) => `
        <div class="flex items-center justify-between text-xs py-1">
            <span class="truncate">${item.image_fabric ?? "-"} · ${item.type_color + " Warna"?? "-"} · ${item.total_long_fabric + " Meter"?? "-"} </span>
            <span class="font-medium">${formatCurrency(item.total_fee)}</span>
        </div>`;

    const batchCard = (batch) => {
        const isDeleted = batch.deleted_at !== null;
        const items = batch.items ?? [];

        return `
        <div class="p-3 rounded-lg bg-(--color-light-gray)/50 dark:bg-(--color-dark-slate)/50 space-y-2
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="flex items-center justify-between gap-2">
                <div class="min-w-0">
                    <p class="text-sm font-medium truncate">
                        ${batch.count} Sablon${batch.notes ? " · " + batch.notes : ""}
                    </p>
                    <p class="text-xs text-(--color-gray)">${batch.date_bill ?? "-"}</p>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    ${
                        isDeleted
                            ? `
                            <button data-batch="${batch.batch}" class="btn-restore p-1.5 rounded-lg text-(--color-success) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-4"></i>
                            </button>
                            <button data-batch="${batch.batch}" class="btn-force-delete p-1.5 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash" class="size-4"></i>
                            </button>`
                            : `
                            <a href="${route("bill_suppliers.batch.edit", batch.batch)}" class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="square-pen" class="size-4"></i>
                            </a>
                            <button data-batch="${batch.batch}" class="btn-delete p-1.5 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>`
                    }
                </div>
            </div>

            <div class="border-t border-(--color-gray)/20 pt-2 space-y-0.5">
                ${items.length ? items.map(batchItemRow).join("") : `<p class="text-xs text-(--color-gray)">Tidak ada rincian</p>`}
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                <span class="text-xs font-semibold text-(--color-dark) dark:text-(--color-light)">Total</span>
                <span class="font-bold text-sm text-(--color-primary)">${formatCurrency(batch.total_fee)}</span>
            </div>
        </div>`;
    };

    const weekSection = (week) => `
        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-5">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <h3 class="font-semibold">${week.week_label}</h3>
                <div class="flex items-center gap-4 text-xs">
                    <span class="text-(--color-red)">Belum Lunas: ${formatCurrency(week.total_unpaid)}</span>
                    <span class="text-(--color-success)">Lunas: ${formatCurrency(week.total_paid)}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium text-(--color-red) mb-2 uppercase">Belum Lunas</p>
                    <div class="space-y-2">
                        ${week.unpaid.length ? week.unpaid.map(batchCard).join("") : `<p class="text-xs text-(--color-gray)">Tidak ada data</p>`}
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-(--color-success) mb-2 uppercase">Lunas</p>
                    <div class="space-y-2">
                        ${week.paid.length ? week.paid.map(batchCard).join("") : `<p class="text-xs text-(--color-gray)">Tidak ada data</p>`}
                    </div>
                </div>
            </div>
        </div>`;

    const load = async () => {
        $("#bs-loading").removeClass("hidden");
        $("#bs-weeks").addClass("hidden");
        $("#bs-empty").addClass("hidden");

        try {
            const response = await ApiProvider.get(
                route("bill_suppliers.by-supplier", supplierId),
            );
            const weeks = response.data ?? [];

            if (!weeks.length) {
                $("#bs-empty").removeClass("hidden");
                return;
            }

            $("#bs-weeks")
                .removeClass("hidden")
                .html(weeks.map(weekSection).join(""));

            if (window.HSStaticMethods) window.HSStaticMethods.autoInit();
            if (window.lucide) window.lucide.createIcons();
        } catch (error) {
            console.error("Load bill supplier error:", error);
        } finally {
            $("#bs-loading").addClass("hidden");
        }
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", async function () {
            const batch = $(this).data("batch");
            const confirmed = await Confirm.delete(
                "Yakin ingin menghapus batch tagihan ini?",
            );
            if (!confirmed) return;
            await ApiProvider.delete(
                route("bill_suppliers.batch.destroy", batch),
            );
            Toast.success("Success", "Batch tagihan deleted successfully");
            load();
        });

        $(document).on("click", ".btn-restore", async function () {
            const batch = $(this).data("batch");
            const confirmed = await Confirm.show(
                "Restore batch tagihan ini?",
                "Confirmation",
            );
            if (!confirmed) return;
            await ApiProvider.put(route("bill_suppliers.batch.restore", batch));
            Toast.success("Success", "Batch tagihan restored");
            load();
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const batch = $(this).data("batch");
            const confirmed = await Confirm.delete(
                "Ini akan menghapus permanen batch tagihan. Lanjutkan?",
            );
            if (!confirmed) return;
            await ApiProvider.delete(
                route("bill_suppliers.batch.force-delete", batch),
            );
            Toast.success("Success", "Batch tagihan permanently deleted");
            load();
        });
    };

    return {
        init() {
            supplierId = $("#bill-supplier-show").data("supplier-id");
            bindEvents();
            load();
        },
    };
})();

$(function () {
    PageScript.init();
});
