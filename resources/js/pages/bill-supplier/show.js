import ApiProvider from "@/utils/api-provider";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    let supplierId;

    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;

    const summaryCard = (label, value, colorClass, icon) => `
        <div class="p-4 rounded-xl bg-(--color-light) dark:bg-(--color-dark) shadow flex items-center gap-3">
            <div class="p-2.5 rounded-lg ${colorClass}/10 shrink-0">
                <i data-lucide="${icon}" class="size-5 ${colorClass}"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-(--color-gray) uppercase tracking-wide">${label}</p>
                <p class="text-lg font-bold truncate">${value}</p>
            </div>
        </div>`;

    const renderSummary = (weeks) => {
        const totalUnpaid = weeks.reduce((sum, w) => sum + w.total_unpaid, 0);
        const totalPaid = weeks.reduce((sum, w) => sum + w.total_paid, 0);
        const totalBatches = weeks.reduce(
            (sum, w) => sum + w.unpaid.length + w.paid.length,
            0,
        );

        $("#bs-summary")
            .removeClass("hidden")
            .html(
                [
                    summaryCard(
                        "Belum Lunas",
                        formatCurrency(totalUnpaid),
                        "text-(--color-red)",
                        "alert-circle",
                    ),
                    summaryCard(
                        "Lunas",
                        formatCurrency(totalPaid),
                        "text-(--color-success)",
                        "check-circle-2",
                    ),
                    summaryCard(
                        "Total Batch",
                        totalBatches,
                        "text-(--color-primary)",
                        "layers",
                    ),
                ].join(""),
            );
    };

    const batchItemRow = (item) => `
        <div class="flex items-center justify-between gap-3 text-sm py-1.5">
            <span class="truncate text-(--color-dark) dark:text-(--color-light)/90">
                <span class="font-medium">${item.type_fabric ?? "-"} ${item.image_fabric ?? "-"}</span>
                <span class="text-(--color-dark) dark:text-(--color-light)/90">· ${item.type_color ?? "-"} Warna · ${item.total_long_fabric ?? "-"} Meter</span>
            </span>
            <span class="font-semibold shrink-0">${formatCurrency(item.total_fee)}</span>
        </div>`;

    const batchCard = (batch) => {
        const isDeleted = batch.deleted_at !== null;
        const items = batch.items ?? [];
        const statusBadge = batch.is_paid
            ? `<span class="text-xs font-semibold px-2 py-1 rounded-full bg-(--color-success)/10 text-(--color-success)">Lunas</span>`
            : `<span class="text-xs font-semibold px-2 py-1 rounded-full bg-(--color-red)/10 text-(--color-red)">Belum Lunas</span>`;

        return `
        <div class="p-4 rounded-xl border border-(--color-gray)/10 bg-(--color-light) dark:bg-(--color-dark) shadow-sm space-y-3
            ${isDeleted ? "opacity-60 border-dashed border-(--color-red)/40" : ""}">

            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        ${statusBadge}
                        <span class="text-sm font-semibold">${batch.count} Sablon</span>
                    </div>
                    ${batch.notes ? `<p class="text-sm text-(--color-gray) truncate">${batch.notes}</p>` : ""}
                    <p class="text-xs text-(--color-gray) flex items-center gap-1">
                        <i data-lucide="calendar" class="size-3.5"></i> ${batch.date_bill ?? "-"}
                    </p>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    ${
                        isDeleted
                            ? `
                            <button data-batch="${batch.batch}" title="Restore" class="btn-restore p-2 rounded-lg text-(--color-success) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-4"></i>
                            </button>
                            <button data-batch="${batch.batch}" title="Hapus Permanen" class="btn-force-delete p-2 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash" class="size-4"></i>
                            </button>`
                            : `
                            <a href="${route("bill_suppliers.batch.edit", batch.batch)}" title="Edit" class="p-2 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="square-pen" class="size-4"></i>
                            </a>
                            <button data-batch="${batch.batch}" title="Hapus" class="btn-delete p-2 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>`
                    }
                </div>
            </div>

            <div class="border-t border-(--color-gray)/15 pt-2.5 divide-y divide-(--color-gray)/10">
                ${items.length ? items.map(batchItemRow).join("") : `<p class="text-sm text-(--color-gray) py-1.5">Tidak ada rincian</p>`}
            </div>

            <div class="flex items-center justify-between pt-2.5 border-t border-(--color-gray)/15">
                <span class="text-sm font-semibold">Total</span>
                <span class="font-bold text-base text-(--color-primary)">${formatCurrency(batch.total_fee)}</span>
            </div>
        </div>`;
    };

    const weekSection = (week) => `
        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 md:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-5 pb-4 border-b border-(--color-gray)/15">
                <h3 class="text-base md:text-lg font-bold flex items-center gap-2">
                    <i data-lucide="calendar-days" class="size-5 text-(--color-primary)"></i>
                    ${week.week_label}
                </h3>
                <div class="flex flex-wrap items-center gap-3 text-sm font-medium">
                    <span class="flex items-center gap-1 text-(--color-red)">
                        <i data-lucide="circle-dot" class="size-3.5"></i> ${formatCurrency(week.total_unpaid)}
                    </span>
                    <span class="flex items-center gap-1 text-(--color-success)">
                        <i data-lucide="circle-check" class="size-3.5"></i> ${formatCurrency(week.total_paid)}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                <div class="space-y-3">
                    <p class="text-sm font-bold text-(--color-red) uppercase tracking-wide flex items-center gap-1">
                        <i data-lucide="clock" class="size-4"></i> Belum Lunas (${week.unpaid.length})
                    </p>
                    <div class="space-y-3">
                        ${week.unpaid.length ? week.unpaid.map(batchCard).join("") : `<p class="text-sm text-(--color-gray) py-4 text-center">Tidak ada data</p>`}
                    </div>
                </div>
                <div class="space-y-3">
                    <p class="text-sm font-bold text-(--color-success) uppercase tracking-wide flex items-center gap-1">
                        <i data-lucide="check" class="size-4"></i> Lunas (${week.paid.length})
                    </p>
                    <div class="space-y-3">
                        ${week.paid.length ? week.paid.map(batchCard).join("") : `<p class="text-sm text-(--color-gray) py-4 text-center">Tidak ada data</p>`}
                    </div>
                </div>
            </div>
        </div>`;

    const load = async () => {
        $("#bs-loading").removeClass("hidden");
        $("#bs-weeks").addClass("hidden");
        $("#bs-empty").addClass("hidden");
        $("#bs-summary").addClass("hidden");

        try {
            const response = await ApiProvider.get(
                route("bill_suppliers.by-supplier", supplierId),
            );
            const weeks = response.data ?? [];

            if (!weeks.length) {
                $("#bs-empty").removeClass("hidden");
                return;
            }

            renderSummary(weeks);

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
