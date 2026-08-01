import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";

const statusColor = {
    ON_PROGRESS: "bg-yellow-500/10 text-yellow-600",
    DONE: "bg-blue-500/10 text-blue-600",
    DELIVERED: "bg-green-500/10 text-green-600",
    RETURNED: "bg-red-500/10 text-red-600",
};

const PageScript = (function () {
    let cardgrid;

    const getISOWeekString = (date) => {
        const target = new Date(date.valueOf());
        const dayNr = (date.getDay() + 6) % 7;
        target.setDate(target.getDate() - dayNr + 3);

        const firstThursday = target.valueOf();
        target.setMonth(0, 1);

        if (target.getDay() !== 4) {
            target.setMonth(0, 1 + ((4 - target.getDay() + 7) % 7));
        }

        const week = 1 + Math.round((firstThursday - target) / 604800000);

        return `${target.getFullYear()}-W${String(week).padStart(2, "0")}`;
    };

    const getUrlParams = () => new URLSearchParams(window.location.search);

    const applyFiltersFromUrl = () => {
        const params = getUrlParams();
        const currentWeek = getISOWeekString(new Date());

        $("#filter-week-start").val(params.get("week_start") || currentWeek);
        $("#filter-week-end").val(params.get("week_end") || currentWeek);
    };

    const syncUrl = () => {
        const params = getUrlParams();
        params.set("week_start", $("#filter-week-start").val());
        params.set("week_end", $("#filter-week-end").val());

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState({}, "", newUrl);
    };

    const setDefaultWeekFilters = () => {
        const currentWeek = getISOWeekString(new Date());
        $("#filter-week-start").val(currentWeek);
        $("#filter-week-end").val(currentWeek);
        syncUrl();
    };

    const statusBadgeMap = {
        ON_PROGRESS: "badge-warning",
        DONE: "badge-info",
        DELIVERED: "badge-success",
        RETURNED: "badge-danger",
    };

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
                        ${item.fabric?.date_coming}
                    </p>
                </div>

                <span class="badge ${badge}">
                    ${item.status?.replaceAll("_", " ") ?? "-"}
                </span>
            </div>`;
        const sablonSumaryHtml = `
            <div class="grid grid-cols-2 gap-2">
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Date</p>
                    <p class="text-sm font-medium">${item.date_sablon ?? "-"}</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Total Sablon</p>
                    <p class="text-sm font-medium">${item.total_sablon_formated ?? 0}</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Long Fabric</p>
                    <p class="text-sm font-medium">${item.total_long_fabric ?? 0} m</p>
                </div>
                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Type Color</p>
                    <p class="text-sm font-medium">${item.typeColor?.name ?? "-"} Warna</p>
                </div>
            </div>`;

        const fabricDetailsHtml = item.sablonDetails?.length
            ? `
                    <div class="space-y-1">
                        <p class="text-xs font-semibold">
                            Fabric Details
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
                    <p class="text-xs font-semibold">Employee Details</p>

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
                                                    ${detail.layers ?? 0} Layer
                                                    ${
                                                        detail.is_bon
                                                            ? `• Bon`
                                                            : ""
                                                    }
                                                    ${
                                                        detail.is_paid
                                                            ? `• Paid`
                                                            : ""
                                                    }
                                                    ${
                                                        detail.is_change
                                                            ? `• Ganti ke ${detail.employeeChange?.name ?? "-"}`
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
                            <i data-lucide="trash-2" class="size-3"></i> Deleted
                        </span>

                        <div class="flex items-center gap-1">
                            <button data-id="${item.id}" class="btn-restore p-1.5 rounded-lg text-xs text-(--color-success) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="rotate-ccw" class="size-3.5"></i> Restore
                            </button>
                            <button data-id="${item.id}" class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red) hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                                <i data-lucide="trash" class="size-3.5"></i> Delete
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
                            <a
                                href="${route("sablons.edit", item.id)}"
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

                Toast.success("Success", "Status updated successfully");

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
                        week_start: $("#filter-week-start").val(),
                        week_end: $("#filter-week-end").val(),
                    };
                },
            },
            renderCard,
            pageLength: 12,
            cardClickRoute: (row) => route("sablons.edit", row.id),
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.delete(
                "Are you sure you want to delete this Sablon?",
            );

            if (!confirmed) return;

            try {
                await ApiProvider.delete(route("sablons.destroy", id));
                Toast.success("Success", "Sablon deleted successfully");
                cardgrid.reload();
            } catch (e) {
                console.error(e);
            }
        });

        $(document).on("click", ".btn-restore", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.show(
                "Restore this Sablon?",
                "Confirmation",
            );
            if (!confirmed) return;

            await ApiProvider.put(route("sablons.restore", id));
            Toast.success("Success", "Sablon restored");
            cardgrid.reload();
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const id = $(this).data("id");

            const confirmed = await Confirm.delete(
                "This will permanently delete the Sablon. Continue?",
            );
            if (!confirmed) return;

            await ApiProvider.delete(route("sablons.force-delete", id));
            Toast.success("Success", "Sablon permanently deleted");
            cardgrid.reload();
        });

        $(document).on(
            "change",
            "#filter-week-start, #filter-week-end",
            function () {
                syncUrl();
                cardgrid.reload();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            setDefaultWeekFilters();
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
