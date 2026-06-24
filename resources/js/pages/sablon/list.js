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

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const badge = statusColor[item.status] ?? "bg-gray-500/10 text-gray-600";

        return `
        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3 cursor-pointer
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="flex items-start justify-between">
                <div>
                    <p class="font-semibold text-sm">${item.fabric?.code ?? "-"}</p>
                    <p class="text-xs text-(--color-gray)">${item.supplier?.name ?? "-"}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full font-medium ${badge}">
                    ${item.status?.replaceAll("_", " ") ?? "-"}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-2 text-xs text-(--color-dark) dark:text-(--color-light)">
                <div>
                    <p class="text-(--color-gray)">Date</p>
                    <p class="font-medium">${item.date_sablon ?? "-"}</p>
                </div>
                <div>
                    <p class="text-(--color-gray)">Total Sablon</p>
                    <p class="font-medium">${item.total_sablon ?? 0}</p>
                </div>
                <div>
                    <p class="text-(--color-gray)">Long Fabric</p>
                    <p class="font-medium">${item.total_long_fabric ?? 0}</p>
                </div>
                <div>
                    <p class="text-(--color-gray)">Type Color</p>
                    <p class="font-medium">${item.typeColor?.name + " Warna" ?? "-"}</p>
                </div>
            </div>

            ${item.notes ? `<p class="text-xs text-(--color-gray) line-clamp-2">${item.notes}</p>` : ""}

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                ${
                    isDeleted
                        ? `
                    <span class="text-xs text-(--color-gray) font-medium flex items-center gap-1">
                        <i data-lucide="trash-2" class="size-3"></i> Deleted
                    </span>
                    <div class="flex items-center gap-1">
                        <button data-id="${item.id}"
                            class="btn-restore p-1.5 rounded-lg text-xs text-(--color-success)
                            hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                            <i data-lucide="rotate-ccw" class="size-3.5"></i> Restore
                        </button>
                        <button data-id="${item.id}"
                            class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red)
                            hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                            <i data-lucide="trash" class="size-3.5"></i> Delete
                        </button>
                    </div>
                `
                        : `
                    <span class="text-xs text-(--color-gray)">${item.created_at ?? ""}</span>
                    <div class="flex items-center gap-1">
                        <a href="${route("sablons.edit", item.id)}"
                            class="p-1.5 rounded-lg hover:bg-(--color-gray)/20
                            text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                            <i data-lucide="square-pen" class="size-4"></i>
                        </a>
                        <button data-id="${item.id}"
                            class="btn-delete p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red) cursor-pointer">
                            <i data-lucide="trash-2" class="size-4"></i>
                        </button>
                    </div>
                `
                }
            </div>
        </div>`;
    };

    const CardGrid = () => {
        cardgrid = initCardgrid({
            containerId: "#sablon-cardgrid",
            filterSelector: "#filter-sablon",
            ajax: {
                url: route("sablons.index"),
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
            const confirmed = await Confirm.show("Restore this Sablon?", "Confirmation");
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
    };

    return {
        init() {
            CardGrid();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
