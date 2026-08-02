import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";

const PageScript = (function () {
    let datatable;

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const DataTable = () => {
        datatable = initDatatable({
            table: "#fabrics-datatable",
            filterSelector: "#filter-fabrics",
            rowClickRoute: (row) => route("fabrics.edit", row.id),
            ajax: {
                url: route("fabrics.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-fabrics").val();
                },
            },
            columns: [
                {
                    data: "supplier.name",
                    width: "12%",
                },
                {
                    data: "code",
                    width: "20%",
                    className: "text-center",
                },
                {
                    data: "seri",
                    width: "8%",
                    className: "text-center dt-body-center",
                },
                {
                    data: "total_inventory_fabric",
                    width: "35%",
                    className: "dt-body-center",
                    render(data) {
                        if (!data || !data.colors || data.colors.length === 0) {
                            return `<span class="text-gray-400 text-sm">-</span>`;
                        }

                        const statusBadgeMap = {
                            ON_PROGRESS: "badge-warning",
                            DONE: "badge-info",
                            DELIVERED: "badge-success",
                            RETURNED: "badge-danger",
                        };

                        const excessParts = data.colors
                            .filter((c) => c.excess > 0)
                            .map((c) => `+ ${c.excess} ${c.name}`)
                            .join(" ");

                        const seriText = excessParts
                            ? `${data.total_pcs} pcs / ${data.seri} seri (${excessParts})`
                            : `${data.total_pcs} pcs total / ${data.seri} seri`;

                        let rows = data.colors.map((color) => {
                            const dotColor = color.color || "#9ca3af";

                            let badges = (color.statuses || [])
                                .filter((s) => s.count > 0)
                                .map((s) => {
                                    const cls = statusBadgeMap[s.status] || "badge-primary";
                                    return `
                                        <span class="badge ${cls}">
                                            ${s.label}: ${s.count}
                                        </span>
                                    `;
                                })
                                .join("");

                            if (!badges) {
                                badges = `<span class="text-[11px] text-gray-400">Belum ada sablon</span>`;
                            }

                            return `
                                <div class="flex items-center justify-between px-3 py-1.5 gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="size-2.5 rounded-full shrink-0" style="background-color: ${dotColor}"></span>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">${color.name ?? "-"}</span>
                                        <span class="text-xs text-gray-400">(${color.stock} pcs)</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-1 justify-end">
                                        ${badges}
                                    </div>
                                </div>
                            `;
                        }).join("");

                        return `
                            <div class="rounded-xl border border-(--color-gray)/20
                                dark:border-(--color-dark-gray)/30 overflow-hidden text-left">

                                <div class="px-3 py-2.5 bg-(--color-gray)/10
                                    dark:bg-(--color-dark-gray)/20">

                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="flex items-center justify-center size-7 rounded-lg
                                            bg-(--color-primary)/20
                                            dark:bg-(--color-primary)/10
                                            text-(--color-primary)">
                                            <i data-lucide="layers" class="size-4"></i>
                                        </span>

                                        <div>
                                            <div class="font-semibold text-sm">
                                                ${seriText}
                                            </div>

                                            <div class="text-xs text-(--color-dark-gray)">
                                                ${data.type_fabric ?? "-"}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-[11px] text-(--color-dark-gray)">
                                        Incoming : ${data.date_coming ?? "-"}
                                    </div>
                                </div>

                                <div class="divide-y divide-(--color-gray)/10
                                    dark:divide-(--color-dark-gray)/20">
                                    ${rows}
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: "notes",
                    width: "20%",
                    render(data) {
                        return `
                            <div class="whitespace-pre-line">
                                ${data ?? "-"}
                            </div>
                        `;
                    }
                },
                {
                    data: "id",
                    width: "5%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(id, type, row) {
                        const isDeleted = row.deleted_at !== null;

                        return `
                        <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
                            <button type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-3 px-3 py-2
                                text-sm font-medium rounded-lg
                                bg-(--color-gray)/20 dark:bg-(--color-dark-gray)/20
                                hover:bg-(--color-gray)/40
                                cursor-pointer">
                                <i data-lucide="ellipsis-vertical" class="size-4"></i>
                            </button>

                            <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10
                                transition-[margin,opacity] opacity-0 duration-300
                                w-auto bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
                                shadow-md rounded-lg p-2"
                                role="menu" aria-orientation="vertical">

                                <div class="p-1 space-y-0.5">

                                ${
                                    !isDeleted
                                        ? `
                                        <a href="${route("fabrics.edit", id)}"
                                            class="flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="square-pen" class="size-4"></i>
                                            Edit
                                        </a>

                                        <button type="button" data-fabric-id="${id}"
                                            class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                            Delete
                                        </button>
                                        `
                                        : `
                                        <button type="button" data-fabric-id="${id}"
                                            class="btn-restore w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-success) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                            Restore
                                        </button>

                                        <button type="button" data-fabric-id="${id}"
                                            class="btn-force-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash" class="size-4"></i>
                                            Force Delete
                                        </button>
                                        `
                                }
                                </div>
                            </div>
                        </div>
                        `;
                    },
                },
            ],
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const fabricId = $(this).data("fabric-id");
            handleDelete(fabricId);
        });
    };

    const handleDelete = async (fabricId) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this fabric? This action cannot be undone.",
        );

        if (!confirmed) {
            return;
        }

        try {
            await ApiProvider.delete(route("fabrics.destroy", fabricId));
            Toast.success("Success", "User deleted successfully");

            reloadDatatable();
        } catch (error) {
            console.error("Delete fabric error:", error);
        }
    };

    $(document).on("click", ".btn-restore", function () {
        const id = $(this).data("fabric-id");
        handleRestore(id);
    });

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            "Restore this fabric?",
            "Confirmation",
        );

        if (!confirmed) return;

        await ApiProvider.put(route("fabrics.restore", id));

        Toast.success("Success", "User restored");
        reloadDatatable();
    };

    $(document).on("click", ".btn-force-delete", function () {
        const id = $(this).data("fabric-id");
        handleForceDelete(id);
    });

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "This will permanently delete the fabric. Continue?",
        );

        if (!confirmed) return;

        await ApiProvider.delete(route("fabrics.force-delete", id));

        Toast.success("Success", "User permanently deleted");
        reloadDatatable();
    };

    return {
        init() {
            DataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
