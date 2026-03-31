import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";

const PageScript = (function () {
    let cardgrid;

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;

        return `
        <div class="h-80 bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="aspect-square rounded-lg bg-(--color-gray)/20 dark:bg-(--color-dark-gray)/20
                flex items-center justify-center overflow-hidden">
                ${
                    item.image_url
                        ? `<img src="${item.image_url}" alt="${item.name}" class="w-full h-full object-cover rounded-lg">`
                        : `<i data-lucide="image" class="size-10 text-(--color-gray)"></i>`
                }
            </div>

            <div class="flex-1">
                <p class="font-semibold text-sm truncate">${item.name}</p>
                ${item.notes ? `<p class="text-xs text-(--color-gray) mt-1 line-clamp-2">${item.notes}</p>` : ""}
            </div>

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
                            hover:bg-(--color-gray)/20 flex items-center gap-1">
                            <i data-lucide="rotate-ccw" class="size-3.5"></i> Restore
                        </button>
                        <button data-id="${item.id}"
                            class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red)
                            hover:bg-(--color-gray)/20 flex items-center gap-1">
                            <i data-lucide="trash" class="size-3.5"></i> Delete
                        </button>
                    </div>
                `
                        : `
                    <span class="text-xs text-(--color-gray)">${item.created_at ?? ""}</span>
                    <div class="flex items-center gap-1">
                        <a href="${route("image_fabrics.edit", item.id)}"
                            class="p-1.5 rounded-lg hover:bg-(--color-gray)/20
                            text-(--color-dark) dark:text-(--color-light)">
                            <i data-lucide="square-pen" class="size-4"></i>
                        </a>
                        <button data-id="${item.id}"
                            class="btn-delete p-1.5 rounded-lg hover:bg-(--color-gray)/20 text-(--color-red)">
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
            containerId: "#image-fabric-cardgrid",
            filterSelector: "#filter-image-fabric",
            ajax: {
                url: route("image_fabrics.index"),
            },
            renderCard,
            pageLength: 12,
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.delete(
                "Are you sure you want to delete this Image Fabric?",
            );
            if (!confirmed) return;
            try {
                await ApiProvider.delete(route("image_fabrics.destroy", id));
                Toast.success("Success", "Image Fabric deleted successfully");
                cardgrid.reload();
            } catch (e) {
                console.error(e);
            }
        });

        $(document).on("click", ".btn-restore", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.show(
                "Restore this Image Fabric?",
                "Confirmation",
            );
            if (!confirmed) return;
            await ApiProvider.put(route("image_fabrics.restore", id));
            Toast.success("Success", "Image Fabric restored");
            cardgrid.reload();
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.delete(
                "This will permanently delete the Image Fabric. Continue?",
            );
            if (!confirmed) return;
            await ApiProvider.delete(route("image_fabrics.force-delete", id));
            Toast.success("Success", "Image Fabric permanently deleted");
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
