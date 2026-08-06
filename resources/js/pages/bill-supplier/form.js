import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";
import reInitUi from "@/utils/reinit-ui";
import trans from "@/utils/trans";
import {
    sablonHeaderHtml,
    sablonSummaryHtml,
    fabricDetailsHtml,
} from "@/pages/bill-supplier/sablon-card";

const PageScript = (function () {
    let form, mode, batch, supplierId, weekStart, weekEnd;
    const modelName = window.langModels?.BillSupplier ?? "Bill Supplier";

    const renderSablonCards = (sablons) => {
        const listContainer = $("#sablon-modal-list");

        if (!sablons.length) {
            listContainer.addClass("hidden");
            $("#sablon-modal-empty").removeClass("hidden");
            return;
        }

        $("#sablon-modal-empty").addClass("hidden");

        listContainer.html(
            sablons
                .map((s) => {
                    const inBatch = !!s.in_current_batch;
                    const isBilledInAdvance =
                        s.status === "ON_PROGRESS" && !!s.is_billed_in_advance;
                    const disabled =
                        s.status !== "DONE" && !inBatch && !isBilledInAdvance;
                    const checked = mode === "edit" ? inBatch : !disabled;

                    return `
                <label class="flex items-start gap-3 p-3 rounded-lg border border-(--color-gray)/10
                    ${disabled ? "opacity-50 cursor-not-allowed" : "hover:bg-(--color-gray)/10 cursor-pointer"}"
                    data-fabric-name="${(s.image_fabric ?? "").toLowerCase()}">
                    <input type="checkbox" class="checkbox-custom sablon-checkbox mt-1"
                        value="${s.sablon_id}" ${checked ? "checked" : ""} ${disabled ? "disabled" : ""}>
                    <div class="flex-1 min-w-0 space-y-2">
                        ${sablonHeaderHtml(s)}
                        ${sablonSummaryHtml(s)}
                        ${fabricDetailsHtml(s)}
                        ${
                            isBilledInAdvance
                                ? `<p class="text-[11px] text-(--color-warning) flex items-center gap-1">
                                        <i data-lucide="info" class="size-3"></i> ${window.langBillSupplier.form.billed_in_advance}
                                    </p>`
                                : ""
                        }
                        ${
                            disabled
                                ? `<p class="text-[11px] text-(--color-red)">${window.langBillSupplier.form.only_done_disabled}</p>`
                                : ""
                        }
                    </div>
                </label>`;
                })
                .join(""),
        );

        listContainer.removeClass("hidden");
        reInitUi();
        syncSelection();
    };

    const loadSablonList = async () => {
        const listContainer = $("#sablon-modal-list");
        const loadingEl = $("#sablon-modal-loading");
        const emptyEl = $("#sablon-modal-empty");

        loadingEl.removeClass("hidden");
        listContainer.addClass("hidden").empty();
        emptyEl.addClass("hidden");

        try {
            let url = route("bill_suppliers.available-sablons", supplierId);

            if (mode === "edit" && batch) {
                url += `?batch=${encodeURIComponent(batch)}`;
            }

            const response = await ApiProvider.get(url);
            renderSablonCards(response.data ?? []);
        } catch (error) {
            console.error("Load available sablons error:", error);
        } finally {
            loadingEl.addClass("hidden");
        }
    };

    const filterSablonCards = (keyword) => {
        const query = keyword.trim().toLowerCase();

        $("#sablon-modal-list > label").each(function () {
            const name = $(this).data("fabric-name") ?? "";
            $(this).toggleClass(
                "hidden",
                query.length > 0 && !name.includes(query),
            );
        });
    };

    const getCheckedIds = () =>
        $(".sablon-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

    const syncCheckAllState = () => {
        const checkAll = document.getElementById("sablon-check-all");
        if (!checkAll) return;

        const total = $(".sablon-checkbox:not(:disabled)").length;
        const checked = $(".sablon-checkbox:not(:disabled):checked").length;

        checkAll.checked = total > 0 && checked === total;
        checkAll.indeterminate = checked > 0 && checked < total;
    };

    const syncSelection = () => {
        const ids = getCheckedIds();

        $("#sablon-selected-summary").text(
            ids.length
                ? trans("langBillSupplier", "form.selected_summary", {
                      count: ids.length,
                  })
                : window.langBillSupplier.form.no_selected,
        );

        syncCheckAllState();
        loadBulkPreview(ids);
    };

    const renderPreview = (calc) => {
        $("#calc-count").text(
            `${calc.count ?? 0} ${window.langBillSupplier.form.calc_count_suffix}`,
        );
        $("#calc-total-long-fabric").text(`${calc.total_long_fabric ?? 0} m`);
        $("#calc-total-fee").text(
            `Rp${RupiahInput.format(calc.total_fee ?? 0)}`,
        );

        $("#calc-item-list").html(
            (calc.items ?? [])
                .map(
                    (item) => `
                    <div class="flex items-center justify-between text-xs py-1">
                        <span class="truncate">${item.image_fabric ?? "-"} · ${item.type_color ?? "-"} ${window.langBillSupplier.sablon_card.type_color_suffix} · ${item.total_long_fabric ?? 0} Meter</span>
                        <span class="font-medium">Rp${RupiahInput.format(item.total_fee ?? 0)}</span>
                    </div>`,
                )
                .join(""),
        );

        if (calc.has_missing_price) {
            Toast.warning(
                window.langCustomAlert.warning,
                window.langBillSupplier.form.some_sablons_no_price,
            );
        }
    };

    const loadBulkPreview = async (ids) => {
        const preview = $("#bs-calc-preview");

        if (!ids.length) {
            preview.addClass("hidden");
            return;
        }

        try {
            const calc = await ApiProvider.post(
                route("bill_suppliers.calculate-bulk"),
                {
                    sablon_ids: ids,
                },
            );
            renderPreview(calc);
            preview.removeClass("hidden");
        } catch (error) {
            console.error("Calculate bulk error:", error);
            preview.addClass("hidden");
        }
    };

    const buildSupplierUrl = () => {
        const params = new URLSearchParams();
        if (weekStart) params.set("week_start", weekStart);
        if (weekEnd) params.set("week_end", weekEnd);

        const query = params.toString();
        const baseUrl = route("bill_suppliers.by-supplier", supplierId);

        return query ? `${baseUrl}?${query}` : baseUrl;
    };

    const submitForm = async (submitter) => {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        const sablonIds = getCheckedIds();

        if (!sablonIds.length) {
            Toast.error(
                window.langCustomAlert.warning,
                window.langBillSupplier.form.select_min_error,
            );
            stopLoading(submitter);
            return;
        }

        payload.sablon_ids = sablonIds;
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("bill_suppliers.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("bill_suppliers.batch.update", batch),
                    payload,
                );
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
            }

            window.location.href = buildSupplierUrl();
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const bindEvents = () => {
        $(document).on("change", "#sablon-check-all", function () {
            $(".sablon-checkbox:not(:disabled)").prop("checked", this.checked);
            syncSelection();
        });

        $(document).on("change", ".sablon-checkbox", function () {
            syncSelection();
        });

        $(document).on("input", "#sablon-search", function () {
            filterSablonCards($(this).val());
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;

            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }

            await submitForm(submitter);
        });
    };

    return {
        init() {
            form = document.getElementById("bill-supplier-form");
            if (!form) return;

            mode = form.dataset.mode;
            batch = form.dataset.batch;
            supplierId = form.dataset.supplierId;
            weekStart = form.dataset.weekStart;
            weekEnd = form.dataset.weekEnd;

            bindEvents();

            loadSablonList();
        },
    };
})();

$(function () {
    PageScript.init();
});
