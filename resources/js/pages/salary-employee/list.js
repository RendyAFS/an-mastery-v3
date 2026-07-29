import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import { initLucide } from "@/utils/lucide";
import {
    isoWeekToDateStr,
    dateToIsoWeek,
    currentMonday,
    toDateStr,
} from "@/utils/week";

const statusColor = {
    PENDING: "bg-yellow-500/10 text-yellow-600",
    PAID: "bg-blue-500/10 text-blue-600",
};

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
    });
};

const PageScript = (function () {
    let cardgrid;
    let currentWeekOf;

    const renderCard = (item) => {
        const badge =
            statusColor[item.status] ?? "bg-gray-500/10 text-gray-600";

        const headerHtml = `
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-sm">${item.employee?.name ?? "-"}</p>
                    <p class="text-sm text-(--color-dark-gray)">${item.date ?? "-"}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium ${badge}">
                    ${item.status ?? "-"}
                </span>
            </div>`;

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
                                        <span>• ${i.image_fabric_name} • ${i.layers ?? 0} Layer</span>
                                        <span class="font-medium">${i.fee_formated}</span>
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
            : `<p class="text-xs text-(--color-dark-gray)">Belum ada data sablon</p>`;

        const additionalFees = Array.isArray(item.additional_fee)
            ? item.additional_fee
            : [];

        const additionalFeeHtml = additionalFees.length
            ? `
                <div class="space-y-1">
                    <p class="text-xs font-semibold">Additional Fee</p>
                    <ul class="space-y-1 text-xs">
                        ${additionalFees
                            .map(
                                (af) => `
                            <li class="flex justify-between text-(--color-dark-gray)">
                                <span>↳ ${af.notes || "Additional Fee"}</span>
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

        return `
        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3">
            ${headerHtml}
            ${groupsHtml}
            ${additionalFeeHtml}

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                <span class="text-sm font-semibold">Total</span>
                <span class="text-sm font-bold">${item.total_formated ?? "Rp 0"}</span>
            </div>

            <div class="flex items-center justify-end gap-1 pt-2 border-t border-(--color-gray)/20">
                <button data-employee-id="${item.employee_id}" data-status="${item.status}"
                    data-additional-fee='${JSON.stringify(additionalFees)}'
                    class="btn-salary-employee p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-xs flex items-center gap-1 cursor-pointer">
                    <i data-lucide="wallet" class="size-3.5"></i> Kelola
                </button>
            </div>
        </div>`;
    };

    const addAdditionalFeeRow = (nominal = "", notes = "") => {
        const tpl = document.getElementById("additional-fee-row-template");
        const row = tpl.content.cloneNode(true);

        const nominalInput = row.querySelector(".af-nominal");
        nominalInput.value = nominal !== "" ? formatSignedRupiah(nominal) : "";

        row.querySelector(".af-notes").value = notes;

        document.getElementById("additional-fee-rows").append(row);

        initLucide();
        bindSignedRupiahInput(nominalInput);
    };

    const initSalaryModal = () => {
        $(document).on("click", ".btn-salary-employee", function (e) {
            e.stopPropagation();

            const employeeId = $(this).data("employee-id");
            const status = $(this).data("status");
            const existing = $(this).data("additional-fee") || [];

            $("#salary-employee-id").val(employeeId);
            $("#salary-week-of").val(currentWeekOf);
            $("#additional-fee-rows").empty();

            const instance = HSSelect.getInstance("#modal-salary-status");
            instance.setValue(status);

            if (existing.length) {
                existing.forEach((af) =>
                    addAdditionalFeeRow(af.nominal, af.notes),
                );
            } else {
                addAdditionalFeeRow();
            }

            window.HSStaticMethods.autoInit();

            HSOverlay.open("#modal-salary-employee");
        });

        $(document).on("click", "#btn-add-additional-fee-row", function () {
            addAdditionalFeeRow();
        });

        $(document).on("click", ".btn-remove-af-row", function () {
            $(this).closest(".additional-fee-row").remove();
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
                    return {
                        nominal,
                        notes: notes || "",
                    };
                })
                .get()
                .filter((af) => af.nominal !== 0 || af.notes !== "");

            try {
                await ApiProvider.put(
                    route("salary-employees.update", employeeId),
                    {
                        week_of: weekOf,
                        status,
                        additional_fee: additionalFee,
                    },
                );

                Toast.success("Success", "Salary updated successfully");

                HSOverlay.close("#modal-salary-employee");

                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });
    };

    const initSyncButton = () => {
        $(document).on("click", "#btn-sync-salary", async function () {
            try {
                await ApiProvider.put(route("salary-employees.sync"), {
                    week_of: currentWeekOf,
                });

                Toast.success("Success", "Salary employee synced successfully");

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
                url: route("salary-employees.index"),
                data: function () {
                    return { week_of: currentWeekOf };
                },
            },
            renderCard,
            pageLength: 12,
        });
    };

    const bindEvents = () => {
        $("#filter-week-salary").on("change", function () {
            const value = $(this).val();
            if (!value) return;

            currentWeekOf = isoWeekToDateStr(value);
            cardgrid.reload();
        });
    };

    return {
        init() {
            const monday = currentMonday();
            currentWeekOf = toDateStr(monday);
            $("#filter-week-salary").val(dateToIsoWeek(monday));

            CardGrid();
            bindEvents();
            initSalaryModal();
            initSyncButton();
        },
    };
})();

$(function () {
    PageScript.init();
});
