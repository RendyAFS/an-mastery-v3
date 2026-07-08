import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import {
    isoWeekToDateStr,
    dateToIsoWeek,
    currentMonday,
    toDateStr,
} from "@/utils/week";

const statusColor = {
    ON_PROGRESS: "bg-yellow-500/10 text-yellow-600",
    DONE: "bg-blue-500/10 text-blue-600",
    DELIVERED: "bg-green-500/10 text-green-600",
    RETURNED: "bg-red-500/10 text-red-600",
};

const PageScript = (function () {
    let cardgrid;
    let currentWeekOf;

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const badge =
            statusColor[item.status] ?? "bg-gray-500/10 text-gray-600";

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
                                const additionalFees = Array.isArray(
                                    detail.additional_fee,
                                )
                                    ? detail.additional_fee
                                    : [];

                                const additionalFeeHtml = additionalFees.length
                                    ? `
                                        <div class="mt-2 ml-4 space-y-1">
                                            ${additionalFees
                                                .map(
                                                    (fee) => `
                                                        <div class="flex justify-between text-[11px] text-(--color-dark-gray)">
                                                            <span>
                                                                ↳ ${fee.notes || "Additional Fee"}
                                                            </span>
                                                            <span class="${
                                                                Number(
                                                                    fee.nominal,
                                                                ) < 0
                                                                    ? "text-(--color-red)"
                                                                    : "text-(--color-success)"
                                                            }">
                                                                ${
                                                                    Number(
                                                                        fee.nominal,
                                                                    ) < 0
                                                                        ? "-"
                                                                        : "+"
                                                                } Rp ${Math.abs(
                                                                    fee.nominal ||
                                                                        0,
                                                                ).toLocaleString(
                                                                    "id-ID",
                                                                )}
                                                            </span>
                                                        </div>
                                                    `,
                                                )
                                                .join("")}
                                        </div>
                                    `
                                    : "";

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
                                                        detail.is_change
                                                            ? `• Ganti ke ${detail.employeeChange?.name ?? "-"}`
                                                            : ""
                                                    }
                                                </p>
                                            </div>

                                            <span class="text-xs font-semibold">
                                                ${detail.total_formated ?? "Rp 0"}
                                            </span>
                                        </div>

                                        ${additionalFeeHtml}
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

            <div class="flex items-start justify-between">
            <div>
                <p class="font-bold text-sm">
                    ${item.supplier?.name ?? "-"} | ${item.imageFabric?.name ?? "-"} | ${item.typeFabric?.name ?? "-"}
                </p>
                <p class="text-sm text-(--color-dark-gray)">
                    ${item.fabric?.date_coming}
                </p>
            </div>

                <span class="text-xs px-2 py-1 rounded-full font-medium ${badge}">
                    ${item.status?.replaceAll("_", " ") ?? "-"}
                </span>
            </div>

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
            </div>
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
                    return { week_of: currentWeekOf };
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

        $("#filter-week-sablon").on("change", function () {
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
            $("#filter-week-sablon").val(dateToIsoWeek(monday));

            CardGrid();
            bindEvents();
            initStatusModal();
        },
    };
})();

$(function () {
    PageScript.init();
});
