import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import trans from "@/utils/trans";
import filterStorage from "@/utils/filter-storage";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getCenteredWeekRange } from "@/utils/week";

const statusBadgeMap = {
    ON_PROGRESS: "badge-warning",
    DONE: "badge-info",
    DELIVERED: "badge-success",
    RETURNED: "badge-danger",
};

const PageScript = (function () {
    let cardgrid;
    const modelName = window.langModels?.Sablon ?? "Sablon";

    const getDefaultRange = () => getCenteredWeekRange(1, 1);

    const applyFiltersFromUrl = () => {
        const params = filterStorage.loadFilterParams();

        if (params.get("supplier_id")) {
            window.setButtonGroupValue(
                "filter-supplier",
                params.get("supplier_id"),
            );
        }

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
        const params = new URLSearchParams();
        params.set("week_start", $("#filter-date-range_start").val());
        params.set("week_end", $("#filter-date-range_end").val());

        const supplierValue = $("#filter-supplier").val();
        if (supplierValue) {
            params.set("supplier_id", supplierValue);
        }

        filterStorage.saveFilterParams(params);
    };

    const setDefaultRangeFilters = () => {
        const instance = getFlatpickrInstance("filter-date-range");
        instance.setDate(getDefaultRange(), true);
    };

    const statusLabel = (status) =>
        window.langSablon?.statuses?.[status] ?? status;

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const badge = statusBadgeMap[item.status] ?? "badge-primary";

        const sablonHeaderHtml = `
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-sm">
                        ${item.supplier?.name ?? "-"} | ${item.imageFabric?.name ?? "-"} | ${item.typeFabric?.name ?? "-"}
                    </p>
                    <p class="text-sm text-(--color-dark-gray)">
                        ${item.date_sablon}
                    </p>
                </div>

                <span class="badge ${badge}">
                    ${statusLabel(item.status) ?? "-"}
                </span>
            </div>`;
        const sablonSumaryHtml = `
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">${window.langSablon.card.date}</p>
                    <p class="text-sm font-medium">${item.date_sablon ?? "-"}</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">${window.langSablon.card.total_sablon}</p>
                    <p class="text-sm font-medium">${item.total_sablon_formated ?? 0}</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">${window.langSablon.card.long_fabric}</p>
                    <p class="text-sm font-medium">${item.total_long_fabric ?? 0} m</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">${window.langSablon.card.type_color}</p>
                    <p class="text-sm font-medium">${item.typeColor?.name ?? "-"} ${window.langSablon.card.type_color_suffix}</p>
                </div>
            </div>`;

        const fabricDetailsHtml = item.sablonDetails?.length
            ? `
                    <div class="space-y-1">
                        <p class="text-xs font-semibold">
                            ${window.langSablon.card.fabric_details}
                        </p>

                        <ul class="space-y-1 text-xs">
                            ${item.sablonDetails
                                .map(
                                    (detail) => `
                                        <li class="flex justify-between">
                                            <span>• ${detail.colorFabric?.name ?? "-"}</span>
                                            <span class="font-medium">${detail.long_fabric ?? 0} m</span>
                                        </li>
                                    `,
                                )
                                .join("")}
                        </ul>
                    </div>
                `
            : "";

        const employeeDetailsHtml = item.sablonEmployeeDetails?.length
            ? `
                <div class="space-y-2">
                    <p class="text-xs font-semibold">${window.langSablon.card.employee_details}</p>

                    <div class="space-y-2">
                        ${item.sablonEmployeeDetails
                            .map((detail) => {
                                return `
                                    <div class="rounded-lg bg-(--color-gray)/10 p-2">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-xs font-medium">
                                                    ${detail.employee?.name ?? "-"}
                                                </p>

                                                <p class="text-[11px] text-(--color-dark-gray)">
                                                    ${detail.layers ?? 0} ${window.langSablon.card.layer_suffix}
                                                    ${
                                                        detail.settlement_of_id
                                                            ? `• <span class="text-(--color-primary) font-bold">${window.langSablon.card.settlement_bon}</span>`
                                                            : ""
                                                    }
                                                    ${
                                                        detail.is_bon &&
                                                        !detail.settlement_of_id
                                                            ? `• <span class="text-(--color-red) font-bold">${window.langSablon.card.bon}</span>`
                                                            : ""
                                                    }
                                                    ${
                                                        detail.is_paid
                                                            ? `• ${window.langSablon.card.paid}`
                                                            : ""
                                                    }
                                                    ${
                                                        detail.is_change
                                                            ? `• ${window.langSablon.card.change_to.replace(":name", detail.employeeChange?.name ?? "-")}`
                                                            : ""
                                                    }
                                                </p>
                                            </div>

                                            <span class="text-xs font-semibold">
                                                ${detail.fee_formated ?? "Rp 0"}
                                            </span>
                                        </div>
                                    </div>
                                `;
                            })
                            .join("")}
                    </div>
                </div>
            `
            : "";

        return `
        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3 cursor-pointer ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">
            ${sablonHeaderHtml}
            ${sablonSumaryHtml}
            ${fabricDetailsHtml}
            ${employeeDetailsHtml}
            ${item.notes ? `<p class="text-xs text-(--color-dark-gray) line-clamp-2">${item.notes}</p>` : ""}
            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                ${
                    isDeleted
                        ? `
                        <span class="text-xs text-(--color-dark-gray) font-medium flex items-center gap-1">
                            <i data-lucide="trash-2" class="size-3"></i> ${window.langSablon.card.deleted}
                        </span>

                        <div class="flex items-center gap-1">
                            <button data-id="${item.id}" class="btn-restore p-1.5 rounded-lg text-xs text-(--color-success) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-3.5"></i> ${window.langSablon.card.restore}
                            </button>
                            <button data-id="${item.id}" class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="trash" class="size-3.5"></i> ${window.langSablon.card.delete}
                            </button>
                        </div>
                    `
                        : `
                        <span class="text-xs text-(--color-dark-gray)">
                            ${item.created_at ?? ""}
                        </span>

                        <div class="flex items-center gap-1">
                        <button class="btn-status p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer"
                            data-id="${item.id}"
                            data-status="${item.status}">
                            <i data-lucide="badge-check" class="size-4"></i>
                        </button>
                        <a href="${route("sablons.edit", item.id)}"
                            class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                            <i data-lucide="square-pen" class="size-4"></i>
                        </a>

                            <button data-id="${item.id}" class="btn-delete p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                        </div>
                    `
                }
            </div>
        </div>`;
    };

    const initStatusModal = () => {
        $(document).on("click", ".btn-status", function (e) {
            e.stopPropagation();

            const id = $(this).data("id");
            const status = $(this).data("status");

            $("#status-sablon-id").val(id);

            const instance = HSSelect.getInstance("#modal-status");
            instance.setValue(status);

            window.HSStaticMethods.autoInit();

            HSOverlay.open("#modal-update-status");
        });

        $(document).on("click", "#btn-save-status", async function () {
            const id = $("#status-sablon-id").val();
            const status = $("#modal-status").val();

            try {
                await ApiProvider.put(route("sablons.update-status", id), {
                    status,
                });

                Toast.success(
                    window.langCustomAlert.success,
                    window.langSablon.status_updated_success,
                );

                HSOverlay.close("#modal-update-status");

                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });
    };

    const CardGrid = () => {
        cardgrid = initCardgrid({
            containerId: "#sablon-cardgrid",
            filterSelector: "#filter-sablon",
            ajax: {
                url: route("sablons.index"),
                data: function () {
                    return {
                        week_start: $("#filter-date-range_start").val(),
                        week_end: $("#filter-date-range_end").val(),
                        supplier_id: $("#filter-supplier").val(),
                    };
                },
            },
            renderCard,
            pageLength: 48,
            cardClickRoute: (row) => route("sablons.edit", row.id),
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.show(
                window.langSablon.delete_confirm_message,
                trans("langCrud", "delete_confirm_title"),
                window.langCustomAlert.delete,
                window.langCustomAlert.cancel,
            );

            if (!confirmed) return;

            try {
                await ApiProvider.delete(route("sablons.destroy", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "deleted", { model: modelName }),
                );
                cardgrid.reload();
            } catch (e) {
                console.error(e);
            }
        });

        $(document).on("click", ".btn-restore", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.show(
                window.langSablon.restore_confirm_message,
                trans("langCrud", "restore_confirm_title"),
            );
            if (!confirmed) return;

            try {
                await ApiProvider.put(route("sablons.restore", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "restored", { model: modelName }),
                );
                cardgrid.reload();
            } catch (e) {
                console.error(e);
            }
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.show(
                window.langSablon.force_delete_confirm_message,
                trans("langCrud", "force_delete_confirm_title"),
                window.langUi?.["Force Delete"],
                window.langCustomAlert.cancel,
            );
            if (!confirmed) return;

            try {
                await ApiProvider.delete(route("sablons.force-delete", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "force_deleted", { model: modelName }),
                );
                cardgrid.reload();
            } catch (e) {
                console.error(e);
            }
        });

        $(document).on("change", "#filter-supplier", function () {
            syncUrl();
            cardgrid.reload();
        });

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
    };

    return {
        init() {
            applyFiltersFromUrl();
            syncUrl();

            CardGrid();
            bindEvents();
            initStatusModal();
        },
    };
})();

$(function () {
    PageScript.init();
});
