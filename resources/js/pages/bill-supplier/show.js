import ApiProvider from "@/utils/api-provider";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    let supplierId;

    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;

    const billRow = (bill) => {
        const isDeleted = bill.deleted_at !== null;

        return `
        <div class="flex items-center justify-between gap-3 p-3 rounded-lg
            bg-(--color-light-gray)/50 dark:bg-(--color-dark-slate)/50
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="min-w-0">
                <p class="text-sm font-medium truncate">${bill.sablon?.fabric ?? "-"} · ${bill.sablon?.type_color ?? "-"}</p>
                <p class="text-xs text-(--color-gray)">${bill.date_bill ?? "-"} · ${bill.sablon?.total_long_fabric ?? 0} m</p>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <span class="font-semibold text-sm">${formatCurrency(bill.total_fee)}</span>

                ${
                    isDeleted
                        ? `
                        <div class="flex items-center gap-1">
                            <button data-id="${bill.id}" class="btn-restore p-1.5 rounded-lg text-(--color-success) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-4"></i>
                            </button>
                            <button data-id="${bill.id}" class="btn-force-delete p-1.5 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash" class="size-4"></i>
                            </button>
                        </div>`
                        : `
                        <div class="flex items-center gap-1">
                            <a href="${route("bill_suppliers.edit", bill.id)}" class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="square-pen" class="size-4"></i>
                            </a>
                            <button data-id="${bill.id}" class="btn-delete p-1.5 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                        </div>`
                }
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
                        ${week.unpaid.length ? week.unpaid.map(billRow).join("") : `<p class="text-xs text-(--color-gray)">Tidak ada data</p>`}
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-(--color-success) mb-2 uppercase">Lunas</p>
                    <div class="space-y-2">
                        ${week.paid.length ? week.paid.map(billRow).join("") : `<p class="text-xs text-(--color-gray)">Tidak ada data</p>`}
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
            const id = $(this).data("id");
            const confirmed = await Confirm.delete(
                "Yakin ingin menghapus bill supplier ini?",
            );
            if (!confirmed) return;
            await ApiProvider.delete(route("bill_suppliers.destroy", id));
            Toast.success("Success", "Bill Supplier deleted successfully");
            load();
        });

        $(document).on("click", ".btn-restore", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.show(
                "Restore bill supplier ini?",
                "Confirmation",
            );
            if (!confirmed) return;
            await ApiProvider.put(route("bill_suppliers.restore", id));
            Toast.success("Success", "Bill Supplier restored");
            load();
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.delete(
                "Ini akan menghapus permanen bill supplier. Lanjutkan?",
            );
            if (!confirmed) return;
            await ApiProvider.delete(route("bill_suppliers.force-delete", id));
            Toast.success("Success", "Bill Supplier permanently deleted");
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
