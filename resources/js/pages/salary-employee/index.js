import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import { startLoading, stopLoading } from "@/utils/button-loading";
import { initLucide } from "@/utils/lucide";
import trans from "@/utils/trans";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getDefaultWeekRange } from "@/utils/week";

const formatSignedRupiah = (value) => {
    const raw = String(value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");

    if (!digits) return isNegative ? "-" : "";

    const formatted = Number(digits).toLocaleString("id-ID");

    return isNegative ? `-${formatted}` : formatted;
};

const unformatSignedRupiah = (el) => {
    if (!el) return 0;

    const raw = String(el.value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");

    if (!digits) return 0;

    const value = Number(digits);

    return isNegative ? -value : value;
};

const bindSignedRupiahInput = (el) => {
    el.addEventListener("input", () => {
        const cursorAtEnd =
            el.selectionStart === el.value.length &&
            el.selectionEnd === el.value.length;

        el.value = formatSignedRupiah(el.value);

        if (cursorAtEnd) {
            el.setSelectionRange(el.value.length, el.value.length);
        }

        const row = el.closest(".additional-fee-row");
        const notesEl = row?.querySelector(".af-notes");
        if (notesEl?.dataset.autoFilled === "true" && el.value.trim() === "") {
            notesEl.value = "";
            delete notesEl.dataset.autoFilled;
        }
    });
};

const handleQuickAfAmount = (btn) => {
    const row = btn.closest(".additional-fee-row");
    const target = row?.querySelector(".af-nominal");
    if (!target) return;

    target.value = formatSignedRupiah(btn.dataset.quickAfAmount);
    target.dispatchEvent(new Event("input", { bubbles: true }));

    const notesEl = row.querySelector(".af-notes");
    if (notesEl && !notesEl.value.trim()) {
        notesEl.value = "Bonus";
        notesEl.dataset.autoFilled = "true";
    }
};

const bindNotesManualEdit = (el) => {
    el.addEventListener("input", () => {
        delete el.dataset.autoFilled;
    });
};

const PageScript = (function () {
    let cardgrid;
    const modelName = window.langModels?.SalaryEmployee ?? "Salary Employee";

    const getUrlParams = () => new URLSearchParams(window.location.search);

    const getDefaultRange = () => getDefaultWeekRange(1);

    const applyFiltersFromUrl = () => {
        const params = getUrlParams();
        const instance = getFlatpickrInstance("filter-date-range");

        const start = params.get("week_start");
        const end = params.get("week_end");

        if (start && end) {
            instance.setDate([start, end], true);
        } else {
            instance.setDate(getDefaultRange(), true);
        }
    };

    const syncUrl = () => {
        const params = getUrlParams();
        params.set("week_start", $("#filter-date-range_start").val());
        params.set("week_end", $("#filter-date-range_end").val());

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState({}, "", newUrl);
    };

    const setDefaultRangeFilters = () => {
        const instance = getFlatpickrInstance("filter-date-range");
        instance.setDate(getDefaultRange(), true);
    };

    const statusBadgeMap = {
        PENDING: "badge-warning",
        PAID: "badge-success",
    };

    const renderCard = (item) => {
        const badge = statusBadgeMap[item.status] ?? "badge-primary";
        const statusLabel =
            window.langEnums?.status_salary_employee?.[item.status] ??
            item.status ??
            "-";

        const headerHtml = `
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-sm">${item.employee?.name ?? "-"}</p>
                    <p class="text-sm text-(--color-dark-gray)">
                        ${item.date ?? "-"}
                        ${item.week_number ? `<span class="text-(--color-dark-gray)">(W-${item.week_number})</span>` : ""}
                    </p>
                </div>
                <span class="badge ${badge}">
                    ${statusLabel}
                </span>
            </div>`;

        const fabricAdjustments = Array.isArray(item.fabric_adjustments)
            ? item.fabric_adjustments
            : [];

        const hasSablonData = item.sablon_groups?.length || fabricAdjustments.length;

        const groupsHtml = item.sablon_groups?.length
            ? `
                <div class="space-y-3">
                    ${item.sablon_groups
                        .map(
                            (group) => `
                            <div class="space-y-1">
                                <p class="text-xs font-semibold">${group.supplier_name}</p>
                                <ul class="space-y-1 text-xs">
                                    ${group.items
                                        .map(
                                            (i) => `
                                                <li class="flex justify-between">
                                                    <span>
                                                        <span class="${i.is_eligible ? "" : "opacity-40"}">
                                                            • ${i.image_fabric_name} • ${i.layers ?? 0} Layer
                                                            ${
                                                                i.is_bon
                                                                    ? i.is_bon_settled
                                                                        ? `<span class="text-[10px] text-(--color-danger) font-bold">(${window.langSalaryEmployee.card.bon_advance_label})</span>`
                                                                        : `<span class="text-[10px] text-(--color-danger) font-bold">(${window.langSalaryEmployee.card.bon_label})</span>`
                                                                    : ""
                                                            }
                                                                ${
                                                                    i.is_bon_settlement
                                                                        ? `<span class="text-[10px] text-(--color-primary) font-bold">(${window.langSalaryEmployee.card.bon_settlement_label})</span>`
                                                                        : ""
                                                                }
                                                        </span>
                                                        ${i.is_eligible ? "" : `<span class="text-[10px] text-(--color-warning) font-semibold">(${i.status})</span>`}
                                                    </span>
                                                    <span class="flex flex-col items-end ${i.is_eligible ? "" : "opacity-40"}">
                                                        ${
                                                            i.is_bon_settlement &&
                                                            i.deduction_fee != 0
                                                                ? `<span class="text-[12px] text-(--color-red)">${i.original_fee_formated} - ${i.deduction_fee_formated}</span>`
                                                                : ""
                                                        }
                                                        <span class="font-medium">${i.fee_formated}</span>
                                                    </span>
                                                </li>
                                            `,
                                        )
                                        .join("")}
                                </ul>
                            </div>
                        `,
                        )
                        .join("")}
                </div>
            `
            : (hasSablonData ? "" : `<p class="text-xs text-(--color-dark-gray)">${window.langSalaryEmployee.card.no_sablon_data}</p>`);

        const fabricAdjustmentHtml = fabricAdjustments.length
            ? `
                <div class="space-y-1 my-1">
                    <ul class="space-y-1 text-xs">
                        ${fabricAdjustments
                            .map(
                                (fa) => `
                            <li class="flex justify-between text-(--color-dark-gray)">
                                <span>↳ ${fa.notes || "Penyesuaian Kain"}</span>
                                <span class="${Number(fa.nominal) < 0 ? "text-(--color-red)" : "text-(--color-success)"}">
                                    ${Number(fa.nominal) < 0 ? "-" : "+"} Rp ${Math.abs(fa.nominal || 0).toLocaleString("id-ID")}
                                </span>
                            </li>
                        `,
                            )
                            .join("")}
                    </ul>
                </div>
            `
            : "";

        const sablonSubtotalHtml = hasSablonData
            ? `
                ${fabricAdjustmentHtml}
                <div class="flex items-center justify-between text-xs py-2 mt-1 border-y border-(--color-gray)/20">
                    <span class="font-bold">${window.langSalaryEmployee.card.sablon_total ?? "Total Sablon"}</span>
                    <span class="font-bold">${item.fee_formated ?? "Rp 0"}</span>
                </div>
            `
            : "";

        const additionalFees = Array.isArray(item.additional_fee)
            ? item.additional_fee
            : [];

        const previousWeekFees = Array.isArray(item.previous_week_fees)
            ? item.previous_week_fees.filter(
                  (pf) => Number(pf.nominal || 0) > 0,
              )
            : [];

        const previousWeekFeeHtml = previousWeekFees.length
            ? `
                <div class="space-y-1">
                    <p class="text-xs font-semibold text-(--color-warning)">${window.langSalaryEmployee.card.previous_week_fee_title}</p>
                    <ul class="space-y-1 text-xs">
                        ${previousWeekFees
                            .map(
                                (pf) => `
                            <li class="flex justify-between text-(--color-warning)">
                                <span>↳ ${pf.notes}</span>
                                <span class="font-medium">+ Rp ${Number(pf.nominal || 0).toLocaleString("id-ID")}</span>
                            </li>
                        `,
                            )
                            .join("")}
                    </ul>
                </div>
            `
            : "";

        const additionalFeeHtml = additionalFees.length
            ? `
                <div class="space-y-1">
                    <p class="text-xs font-semibold">${window.langSalaryEmployee.card.additional_fee}</p>
                    <ul class="space-y-1 text-xs">
                        ${additionalFees
                            .map(
                                (af) => `
                            <li class="flex justify-between text-(--color-dark-gray)">
                                <span>↳ ${af.notes || window.langSalaryEmployee.card.additional_fee}</span>
                                <span class="${Number(af.nominal) < 0 ? "text-(--color-red)" : "text-(--color-success)"}">
                                    ${Number(af.nominal) < 0 ? "-" : "+"} Rp ${Math.abs(af.nominal || 0).toLocaleString("id-ID")}
                                </span>
                            </li>
                        `,
                            )
                            .join("")}
                    </ul>
                </div>
            `
            : "";

        const presenceHtml = item.presence_total
            ? `
                <div class="flex items-center justify-between text-xs">
                    <span class="font-medium text-(--color-info)">${window.langSalaryEmployee.card.presence}</span>
                    <span class="font-medium text-(--color-info)">${item.presence_total_formated ? "+" + item.presence_total_formated : item.presence_total_formated}</span>
                </div>
            `
            : "";

        const memos = Array.isArray(item.memos) ? item.memos : [];

        const memoHtml = memos.length
            ? `
                <div class="space-y-1">
                    <p class="text-xs font-semibold">${window.langSalaryEmployee.card.memo ?? "Memo"}</p>
                    <ul class="space-y-1 text-xs">
                        ${memos
                            .map(
                                (m) => `
                            <li class="flex justify-between text-(--color-dark-gray)">
                                <span class="flex flex-col">
                                    <span>↳ ${m.name || "-"}</span>
                                    ${m.date ? `<span class="text-[10px] text-(--color-dark-gray)/70">${m.date}</span>` : ""}
                                </span>
                                <span class="${Number(m.nominal) < 0 ? "text-(--color-red)" : "text-(--color-success)"}">
                                    ${Number(m.nominal) < 0 ? "-" : "+"} Rp ${Math.abs(m.nominal || 0).toLocaleString("id-ID")}
                                </span>
                            </li>
                        `,
                            )
                            .join("")}
                    </ul>
                </div>
            `
            : "";

        return `
            <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3">
                ${headerHtml}
                ${groupsHtml}
                ${sablonSubtotalHtml}
                ${previousWeekFeeHtml}
                ${additionalFeeHtml}
                ${memoHtml}
                ${presenceHtml}

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                <span class="text-sm font-semibold">${window.langSalaryEmployee.card.total}</span>
                <span class="text-sm font-bold">${item.total_formated ?? "Rp 0"}</span>
            </div>

            <div class="flex items-center justify-end gap-1 pt-2 border-t border-(--color-gray)/20">
                <button data-employee-id="${item.employee_id}" data-employee-name="${item.employee?.name ?? "-"}" data-status="${item.status}"
                    data-date="${item.date ?? ""}"
                    data-additional-fee='${JSON.stringify(item.all_additional_fee ?? additionalFees)}'
                    data-previous-fee='${JSON.stringify(previousWeekFees)}'
                    class="btn-salary-employee p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-xs flex items-center gap-1 cursor-pointer">
                    <i data-lucide="wallet" class="size-3.5"></i> ${window.langSalaryEmployee.card.manage}
                </button>
            </div>
        </div>`;
    };

    const addAdditionalFeeRow = (nominal = "", notes = "", type = "") => {
        const tpl = document.getElementById("additional-fee-row-template");
        const row = tpl.content.cloneNode(true);

        const nominalInput = row.querySelector(".af-nominal");
        nominalInput.value = nominal !== "" ? formatSignedRupiah(nominal) : "";

        const notesInput = row.querySelector(".af-notes");
        notesInput.value = notes;

        const rowEl = row.querySelector(".additional-fee-row");
        if (rowEl && type) {
            rowEl.dataset.type = type;
        }

        document.getElementById("additional-fee-rows").append(row);

        initLucide();
        bindSignedRupiahInput(nominalInput);
        bindNotesManualEdit(notesInput);
        applyModalLockState();
    };

    const applyModalLockState = () => {
        const locked = $("#modal-salary-status").val() === "PAID";

        $(
            "#additional-fee-rows .af-nominal, #additional-fee-rows .af-notes, .btn-remove-af-row, #btn-add-additional-fee-row",
        ).prop("disabled", locked);

        $("#additional-fee-rows .additional-fee-row").toggleClass(
            "opacity-50 pointer-events-none",
            locked,
        );

        $("#salary-employee-locked-hint").toggleClass("hidden", !locked);
    };

    const initSalaryModal = () => {
        $(document).on("click", ".btn-salary-employee", function (e) {
            e.stopPropagation();

            const employeeId = $(this).data("employee-id");
            const employeeName = $(this).data("employee-name");
            const status = $(this).data("status");
            const date = $(this).data("date");
            const existing = $(this).data("additional-fee") || [];
            const existingPreviousFee = $(this).data("previous-fee") || [];

            $("#salary-employee-id").val(employeeId);
            $("#salary-week-of").val(date);
            $("#salary-employee-name").text(employeeName || "-");
            $("#additional-fee-rows").empty();

            $("#previous-week-fee-rows").empty();
            if (existingPreviousFee.length) {
                $("#previous-week-fee-section").removeClass("hidden");
                existingPreviousFee.forEach((pf) => {
                    $("#previous-week-fee-rows").append(`
                            <div class="flex items-center justify-between p-3 rounded-lg border border-(--color-warning)/40 bg-(--color-warning)/5">
                                <span class="text-sm">${pf.notes}</span>
                                <span class="text-sm font-semibold">Rp ${Number(pf.nominal || 0).toLocaleString("id-ID")}</span>
                            </div>
                        `);
                });
            } else {
                $("#previous-week-fee-section").addClass("hidden");
            }

            const instance = HSSelect.getInstance("#modal-salary-status");
            instance.setValue(status);

            if (existing.length) {
                existing.forEach((af) =>
                    addAdditionalFeeRow(af.nominal, af.notes, af.type),
                );
            } else {
                addAdditionalFeeRow();
            }

            window.HSStaticMethods.autoInit();
            applyModalLockState();

            HSOverlay.open("#hs-salary-employee-modal");
        });

        $(document).on("change", "#modal-salary-status", function () {
            applyModalLockState();
        });

        $(document).on("click", "#btn-add-additional-fee-row", function () {
            addAdditionalFeeRow();
        });

        $(document).on("click", ".btn-remove-af-row", function () {
            $(this).closest(".additional-fee-row").remove();
        });

        $(document).on("mousedown", "[data-quick-af-amount]", function (e) {
            e.preventDefault();
            handleQuickAfAmount(this);
        });

        $(document).on("click", "#btn-save-salary-employee", async function () {
            const employeeId = $("#salary-employee-id").val();
            const weekOf = $("#salary-week-of").val();
            const status = $("#modal-salary-status").val();

            const additionalFee = $("#additional-fee-rows .additional-fee-row")
                .map(function () {
                    const nominalEl = $(this).find(".af-nominal")[0];
                    const nominal = unformatSignedRupiah(nominalEl);
                    const notes = $(this).find(".af-notes").val();
                    const notesLower = (notes || "").trim().toLowerCase();
                    const isFabric =
                        notesLower.startsWith("plus kain") ||
                        notesLower.startsWith("minus kain");
                    const item = {
                        nominal,
                        notes: notes || "",
                    };
                    if (isFabric) {
                        item.type = "fabric_adjustment";
                    }
                    return item;
                })
                .get()
                .filter((af) => af.nominal !== 0 || af.notes !== "");

            try {
                await ApiProvider.put(
                    route("salary_employees.update", employeeId),
                    {
                        week_of: weekOf,
                        status,
                        additional_fee: additionalFee,
                    },
                );

                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );

                HSOverlay.close("#hs-salary-employee-modal");

                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });
    };

    const CardGrid = () => {
        cardgrid = initCardgrid({
            containerId: "#salary-employee-cardgrid",
            filterSelector: "#filter-salary-employee",
            ajax: {
                url: route("salary_employees.index"),
                data: function () {
                    return {
                        week_start: $("#filter-date-range_start").val(),
                        week_end: $("#filter-date-range_end").val(),
                    };
                },
            },
            renderCard,
            pageLength: 48,
        });
    };

    const bindEvents = () => {
        $(document).on(
            "flatpickr:range-change",
            "#filter-date-range",
            function (e) {
                if (!e.detail.start || !e.detail.end) return;
                syncUrl();
                cardgrid.reload();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            setDefaultRangeFilters();
            cardgrid.reload();
        });

        $(document).on("click", "#btn-sync-salary", async function () {
            const weekStart = $("#filter-date-range_start").val();
            const weekEnd = $("#filter-date-range_end").val();

            if (!weekStart || !weekEnd) {
                Toast.error(
                    window.langCustomAlert.warning,
                    window.langSalaryEmployee.sync.select_week_warning,
                );
                return;
            }

            const confirmed = await Confirm.show(
                window.langSalaryEmployee.sync.confirm_message,
                window.langSalaryEmployee.sync.confirm_title,
                window.langCustomAlert.confirm,
                window.langCustomAlert.cancel,
            );

            if (!confirmed) return;

            startLoading(this);

            try {
                const response = await ApiProvider.put(
                    route("salary_employees.sync"),
                    { week_start: weekStart, week_end: weekEnd },
                );
                Toast.success(window.langCustomAlert.success, response.message);
                cardgrid.reload();
            } catch (err) {
                console.error(err);
            } finally {
                stopLoading(this);
            }
        });
    };

    return {
        init() {
            applyFiltersFromUrl();
            syncUrl();

            CardGrid();
            bindEvents();
            initSalaryModal();
        },
    };
})();

$(function () {
    PageScript.init();
});
