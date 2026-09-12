import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import trans from "@/utils/trans";
import filterStorage from "@/utils/filter-storage";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getPastMonthRange } from "@/utils/week";

const PageScript = (function () {
    let cardgrid;
    const modelName = window.langModels?.Fabric ?? "Fabric";

    const getDefaultRange = () => getPastMonthRange(6);

    const applyFiltersFromUrl = () => {
        const params = filterStorage.loadFilterParams();

        if (params.get("supplier_id")) {
            window.setButtonGroupValue(
                "filter-supplier",
                params.get("supplier_id"),
            );
        }

        if (params.get("type_fabric_id")) {
            window.setButtonGroupValue(
                "filter-type-fabric",
                params.get("type_fabric_id"),
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

        const typeFabricValue = $("#filter-type-fabric").val();
        if (typeFabricValue) {
            params.set("type_fabric_id", typeFabricValue);
        }

        filterStorage.saveFilterParams(params);
    };

    const setDefaultRangeFilters = () => {
        const instance = getFlatpickrInstance("filter-date-range");
        instance.setDate(getDefaultRange(), true);
    };

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const data = item.total_inventory_fabric || {};

        const statusBadgeMap = {
            ON_PROGRESS: "badge-warning",
            DONE: "badge-info",
            DELIVERED: "badge-success",
            RETURNED: "badge-danger",
        };

        const excessParts = (data.colors || [])
            .filter((c) => c.excess > 0)
            .map((c) => `+ ${c.excess} ${c.name}`)
            .join(" ");

        const seriText = excessParts
            ? `<span class="font-semibold">${data.total_pcs ?? 0} pcs</span> / <strong class="font-bold text-(--color-primary)">${data.seri ?? 0} seri</strong> (${excessParts})`
            : `<span class="font-semibold">${data.total_pcs ?? 0} pcs</span> total / <strong class="font-bold text-(--color-primary) dark:text-(--color-light-primary)">${data.seri ?? 0} seri</strong>`;

        let rows = (data.colors || [])
            .map((color) => {
                const dotColor = color.color || "#9ca3af";

                let badges = (color.statuses || [])
                    .filter((s) => s.count > 0)
                    .map((s) => {
                        const cls =
                            statusBadgeMap[s.status] || "badge-primary";
                        return `
                        <span class="badge ${cls} px-2! py-0.5! text-xs font-semibold rounded-md whitespace-nowrap">
                            ${s.label}: <strong class="font-bold ml-0.5">${s.count}</strong>
                        </span>
                    `;
                    })
                    .join("");

                if (!badges) {
                    badges = `<span class="text-[11px] text-gray-400 dark:text-gray-500 italic">${window.langFabric?.no_sablon_yet ?? ""}</span>`;
                }

                return `
                <div class="px-3 py-2 space-y-1.5 hover:bg-(--color-gray)/5 dark:hover:bg-(--color-dark-gray)/10 transition">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="size-2.5 rounded-full shrink-0 ring-1 ring-black/10 dark:ring-white/20" style="background-color: ${dotColor}"></span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">${color.name ?? "-"}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-300 bg-(--color-gray)/15 dark:bg-(--color-dark-gray)/30 px-2 py-0.5 rounded-md shrink-0">
                            ${color.stock} pcs
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        ${badges}
                    </div>
                </div>
            `;
            })
            .join("");

        return `
            <div class="relative bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col justify-between gap-3 cursor-pointer ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">
                <div class="space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h3 class="font-bold text-sm text-(--color-dark) dark:text-(--color-light)">
                                ${item.supplier?.name ?? "-"}
                            </h3>
                            <p class="text-xs text-(--color-dark-gray) dark:text-(--color-light-gray)">
                                ${item.type_fabric?.name ?? data.type_fabric ?? "-"}
                            </p>
                        </div>

                        <div class="text-[11px] font-medium text-(--color-dark-gray) dark:text-(--color-light-gray) shrink-0">
                            ${window.langFabric?.incoming_label ?? "Incoming"}: ${item.date_coming ?? data.date_coming ?? "-"}
                        </div>
                    </div>

                    <div class="rounded-xl border border-(--color-gray)/20 dark:border-(--color-dark-gray)/30 overflow-hidden text-left">
                        <div class="px-3 py-2 bg-(--color-gray)/10 dark:bg-(--color-dark-gray)/20 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center justify-center size-7 rounded-lg
                                    bg-(--color-primary)/20
                                    dark:bg-(--color-primary)/10
                                    text-(--color-primary)">
                                    <i data-lucide="layers" class="size-4"></i>
                                </span>

                                <div class="font-semibold text-sm text-(--color-dark) dark:text-(--color-light)">
                                    ${seriText}
                                </div>
                            </div>
                        </div>

                        <div class="divide-y divide-(--color-gray)/10 dark:divide-(--color-dark-gray)/20">
                            ${rows || `<div class="p-3 text-xs text-gray-400 text-center">-</div>`}
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                    <span class="text-xs text-(--color-dark-gray)">
                        ${item.created_at ?? ""}
                    </span>

                    <div class="flex items-center gap-1">
                    ${
                        !isDeleted
                            ? `
                            <a href="${route("fabrics.edit", item.id)}"
                                class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                                <i data-lucide="square-pen" class="size-4"></i>
                            </a>

                            <button type="button" data-fabric-id="${item.id}"
                                class="btn-delete p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
                                <i data-lucide="trash-2" class="size-4"></i>
                            </button>
                        `
                            : `
                            <button type="button" data-fabric-id="${item.id}"
                                class="btn-restore p-1.5 rounded-lg text-xs text-(--color-success) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-3.5"></i> ${window.langFabric?.card?.restore ?? "Restore"}
                            </button>

                            <button type="button" data-fabric-id="${item.id}"
                                class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="trash" class="size-3.5"></i> ${window.langFabric?.card?.delete ?? "Delete"}
                            </button>
                        `
                    }
                    </div>
                </div>
            </div>
        `;
    };

    const CardGrid = () => {
        cardgrid = initCardgrid({
            containerId: "#fabric-cardgrid",
            filterSelector: "#filter-fabric",
            ajax: {
                url: route("fabrics.index"),
                data: function () {
                    return {
                        week_start: $("#filter-date-range_start").val(),
                        week_end: $("#filter-date-range_end").val(),
                        supplier_id: $("#filter-supplier").val(),
                        type_fabric_id: $("#filter-type-fabric").val(),
                    };
                },
            },
            renderCard,
            pageLength: 48,
            cardClickRoute: (row) => route("fabrics.edit", row.id),
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const fabricId = $(this).data("fabric-id");
            handleDelete(fabricId);
        });

        $(document).on("click", ".btn-restore", function () {
            const id = $(this).data("fabric-id");
            handleRestore(id);
        });

        $(document).on("click", ".btn-force-delete", function () {
            const id = $(this).data("fabric-id");
            handleForceDelete(id);
        });

        $(document).on("change", "#filter-supplier, #filter-type-fabric", function () {
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

    const handleDelete = async (fabricId) => {
        const confirmed = await Confirm.show(
            window.langFabric.delete_confirm_message,
            trans("langCrud", "delete_confirm_title"),
            window.langCustomAlert.delete,
            window.langCustomAlert.cancel,
        );

        if (!confirmed) {
            return;
        }

        try {
            await ApiProvider.delete(route("fabrics.destroy", fabricId));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "deleted", { model: modelName }),
            );

            cardgrid.reload();
        } catch (error) {
            console.error("Delete fabric error:", error);
        }
    };

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            window.langFabric.restore_confirm_message,
            trans("langCrud", "restore_confirm_title"),
        );

        if (!confirmed) return;

        try {
            await ApiProvider.put(route("fabrics.restore", id));

            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            cardgrid.reload();
        } catch (error) {
            console.error("Restore fabric error:", error);
        }
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.show(
            window.langFabric.force_delete_confirm_message,
            trans("langCrud", "force_delete_confirm_title"),
            window.langUi?.["Force Delete"],
            window.langCustomAlert.cancel,
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("fabrics.force-delete", id));

            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
            cardgrid.reload();
        } catch (error) {
            console.error("Force delete fabric error:", error);
        }
    };

    return {
        init() {
            applyFiltersFromUrl();
            syncUrl();

            CardGrid();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
