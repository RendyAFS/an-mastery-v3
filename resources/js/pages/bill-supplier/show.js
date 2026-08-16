import ApiProvider from "@/utils/api-provider";
import RupiahInput from "@/utils/rupiah-input";
import trans from "@/utils/trans";
import filterStorage from "@/utils/filter-storage";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getDefaultWeekRange } from "@/utils/week";

const PageScript = (function () {
    let supplierId;
    let weekStart;
    let weekEnd;

    const getDefaultRange = () => getDefaultWeekRange(2);

    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;

    const summaryCard = (label, value, icon) => `
        <div class="rounded-xl bg-white/12 backdrop-blur border border-white/20 p-4 flex items-center gap-3">
            <div class="p-2 rounded-lg bg-white/15 shrink-0">
                <i data-lucide="${icon}" class="size-5"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] uppercase tracking-wide text-white/70">${label}</p>
                <p class="text-lg md:text-xl font-bold truncate">${value}</p>
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
                        window.langBillSupplier.show.summary_unpaid,
                        formatCurrency(totalUnpaid),
                        "alert-circle",
                    ),
                    summaryCard(
                        window.langBillSupplier.show.summary_paid,
                        formatCurrency(totalPaid),
                        "check-circle-2",
                    ),
                    summaryCard(
                        window.langBillSupplier.show.summary_batches,
                        totalBatches,
                        "layers",
                    ),
                ].join(""),
            );
    };

    const emptyBatchState = () => `
        <div class="py-6 text-center rounded-lg border border-dashed border-(--color-gray)/20">
            <p class="text-xs text-(--color-gray)">${window.langBillSupplier.show.no_data}</p>
        </div>`;

    const detailRow = (detail) => `
        <div class="flex items-center justify-between gap-3 text-xs pl-4 py-1 text-(--color-slate) dark:text-(--color-gray)">
            <span class="truncate">${detail.color_fabric} · ${detail.long_fabric} Meter</span>
            <span class="font-medium shrink-0">${formatCurrency(detail.total_fee)}</span>
        </div>`;

    const batchItemRow = (item) => {
        const details = item.details ?? [];

        return `
            <div class="py-1.5 space-y-1">
                <div class="flex items-center justify-between gap-3 text-sm">
                    <span class="truncate text-(--color-dark) dark:text-(--color-light)/90">
                        <span class="font-medium">${item.type_fabric ?? "-"} ${item.image_fabric ?? "-"}</span>
                        <span class="text-(--color-dark) dark:text-(--color-light)/90">· ${item.type_color ?? "-"} ${window.langBillSupplier.sablon_card.type_color_suffix} · ${item.total_long_fabric ?? "-"} Meter</span>
                    </span>
                    <span class="font-semibold shrink-0">${formatCurrency(item.total_fee)}</span>
                </div>
                ${details.length ? details.map(detailRow).join("") : ""}
            </div>`;
    };

    const batchCard = (batch) => {
        const editUrl = `${route("bill_suppliers.batch.edit", batch.batch)}?${new URLSearchParams({ week_start: weekStart ?? "", week_end: weekEnd ?? "" })}`;
        const items = batch.items ?? [];
        const accent = batch.is_paid
            ? "border-l-(--color-success)"
            : "border-l-(--color-red)";
        const statusBadge = batch.is_paid
            ? `<span class="badge badge-success">${window.langBillSupplier.show.paid_badge}</span>`
            : `<span class="badge badge-danger">${window.langBillSupplier.show.unpaid_badge}</span>`;

        return `
        <div class="rounded-xl border border-(--color-gray)/10 border-l-4 ${accent}
            bg-(--color-light-gray)/40 dark:bg-white/3
            hover:bg-(--color-light-gray)/70 dark:hover:bg-white/6
            transition-colors p-4 space-y-3">

            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 space-y-1">
                    <div class="flex flex-wrap items-center gap-2">
                        ${statusBadge}
                        <span class="text-sm font-semibold">${trans("langBillSupplier", "show.sablon_count", { count: batch.count })}</span>
                    </div>
                    ${batch.notes ? `<p class="text-sm text-(--color-gray) truncate">${batch.notes}</p>` : ""}
                    <p class="text-xs text-(--color-gray) flex items-center gap-1">
                        <i data-lucide="calendar" class="size-3.5"></i> ${batch.date_bill ?? "-"}
                    </p>
                </div>

                <div class="flex items-center gap-1 shrink-0">
                    <a href="${editUrl}" title="${window.langBillSupplier.show.edit}" class="p-2 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                        <i data-lucide="square-pen" class="size-4"></i>
                    </a>
                    <button data-batch="${batch.batch}" title="${window.langBillSupplier.show.delete}" class="btn-delete p-2 rounded-lg text-(--color-red) hover:bg-(--color-gray)/20 cursor-pointer">
                        <i data-lucide="trash-2" class="size-4"></i>
                    </button>
                </div>
            </div>

            <div class="border-t border-(--color-gray)/15 pt-2.5 divide-y divide-(--color-gray)/10">
                ${items.length ? items.map(batchItemRow).join("") : `<p class="text-sm text-(--color-gray) py-1.5">${window.langBillSupplier.show.no_detail}</p>`}
            </div>

            <div class="flex items-center justify-between pt-2.5 border-t border-(--color-gray)/15">
                <span class="text-sm font-semibold">${window.langBillSupplier.show.total}</span>
                <span class="font-bold text-base text-(--color-primary)">${formatCurrency(batch.total_fee)}</span>
            </div>
        </div>`;
    };

    const weekSection = (week, index, total) => `
        <div class="${index === total - 1 ? "pb-0" : "pb-8"}">
            <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-2xl shadow p-4 md:p-6 border border-(--color-gray)/10">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5 pb-4 border-b border-(--color-gray)/15">
                    <h3 class="text-base md:text-lg font-bold flex items-center gap-2">
                        <span class="flex items-center justify-center size-8 rounded-full bg-(--color-primary)/10 border-2 border-(--color-primary) text-(--color-primary) shrink-0">
                            <i data-lucide="calendar-days" class="size-4"></i>
                        </span>
                        ${week.week_label}
                    </h3>
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-(--color-red)/10 text-(--color-red)">
                            <i data-lucide="circle-dot" class="size-3"></i> ${formatCurrency(week.total_unpaid)}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-(--color-success)/10 text-(--color-success)">
                            <i data-lucide="circle-check" class="size-3"></i> ${formatCurrency(week.total_paid)}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-(--color-red) uppercase tracking-wide flex items-center gap-1.5">
                            <i data-lucide="clock" class="size-3.5"></i> ${trans("langBillSupplier", "show.unpaid_header", { count: week.unpaid.length })}
                        </p>
                        <div class="space-y-3">
                            ${week.unpaid.length ? week.unpaid.map(batchCard).join("") : emptyBatchState()}
                        </div>
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs font-bold text-(--color-success) uppercase tracking-wide flex items-center gap-1.5">
                            <i data-lucide="check" class="size-3.5"></i> ${trans("langBillSupplier", "show.paid_header", { count: week.paid.length })}
                        </p>
                        <div class="space-y-3">
                            ${week.paid.length ? week.paid.map(batchCard).join("") : emptyBatchState()}
                        </div>
                    </div>
                </div>
            </div>
        </div>`;

    const buildBackLink = () => {
        const params = new URLSearchParams();
        if (weekStart) params.set("week_start", weekStart);
        if (weekEnd) params.set("week_end", weekEnd);

        const query = params.toString();
        const baseUrl = route("bill_suppliers.index");

        $("#bs-back-link").attr(
            "href",
            query ? `${baseUrl}?${query}` : baseUrl,
        );
    };

    const syncUrl = () => {
        const params = new URLSearchParams();
        params.set("week_start", weekStart);
        params.set("week_end", weekEnd);

        filterStorage.saveFilterParams(params);
        buildBackLink();
    };

    const initFilters = () => {
        const instance = getFlatpickrInstance("filter-date-range");

        if (!weekStart || !weekEnd) {
            const stored = filterStorage.loadFilterParams();
            const storedStart = stored.get("week_start");
            const storedEnd = stored.get("week_end");

            if (storedStart && storedEnd) {
                weekStart = weekStart || storedStart;
                weekEnd = weekEnd || storedEnd;
                instance.setDate([weekStart, weekEnd], true);
            } else {
                instance.setDate(getDefaultRange(), true);
                weekStart = $("#filter-date-range_start").val();
                weekEnd = $("#filter-date-range_end").val();
            }
        } else {
            instance.setDate([weekStart, weekEnd], true);
        }

        syncUrl();
    };

    const load = async () => {
        $("#bs-loading").removeClass("hidden");
        $("#bs-weeks").addClass("hidden");
        $("#bs-empty").addClass("hidden");
        $("#bs-summary").addClass("hidden");

        try {
            const params = new URLSearchParams();
            if (weekStart) params.set("week_start", weekStart);
            if (weekEnd) params.set("week_end", weekEnd);

            const query = params.toString();
            const url = query
                ? `${route("bill_suppliers.by-supplier", supplierId)}?${query}`
                : route("bill_suppliers.by-supplier", supplierId);

            const response = await ApiProvider.get(url);
            const weeks = response.data ?? [];

            if (!weeks.length) {
                $("#bs-empty").removeClass("hidden");
                return;
            }

            renderSummary(weeks);

            $("#bs-weeks")
                .removeClass("hidden")
                .html(
                    weeks
                        .map((week, index) =>
                            weekSection(week, index, weeks.length),
                        )
                        .join(""),
                );

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

            const confirmed = await Confirm.show(
                window.langBillSupplier.show.delete_confirm_message,
                trans("langCrud", "delete_confirm_title"),
                window.langCustomAlert.delete,
                window.langCustomAlert.cancel,
            );
            if (!confirmed) return;

            try {
                await ApiProvider.delete(
                    route("bill_suppliers.batch.destroy", batch),
                );
                Toast.success(
                    window.langCustomAlert.success,
                    window.langBillSupplier.show.batch_deleted_success,
                );
                load();
            } catch (err) {
                console.error(err);
            }
        });

        $(document).on(
            "flatpickr:range-change",
            "#filter-date-range",
            function (e) {
                if (!e.detail.start || !e.detail.end) return;
                weekStart = $("#filter-date-range_start").val();
                weekEnd = $("#filter-date-range_end").val();
                syncUrl();
                load();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            const instance = getFlatpickrInstance("filter-date-range");
            instance.setDate(getDefaultRange(), true);
        });
    };

    return {
        init() {
            const container = $("#bill-supplier-show");
            supplierId = container.data("supplier-id");
            weekStart = container.data("week-start") || null;
            weekEnd = container.data("week-end") || null;

            initFilters();
            bindEvents();
            load();
        },
    };
})();

$(function () {
    PageScript.init();
});
