import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import trans from "@/utils/trans";

const PageScript = (function () {
    let cardgrid;
    const modelName = window.langModels?.ImageFabric ?? "Image Fabric";

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;

        return `
        <div class="h-80 bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3 cursor-pointer
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
                        <i data-lucide="trash-2" class="size-3"></i> ${window.langUi?.Deleted ?? "Deleted"}
                    </span>
                    <div class="flex items-center gap-1">
                        <button data-id="${item.id}"
                            class="btn-restore p-1.5 rounded-lg text-xs text-(--color-success)
                            hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                            <i data-lucide="rotate-ccw" class="size-3.5"></i> ${window.langUi?.Restore ?? "Restore"}
                        </button>
                        <button data-id="${item.id}"
                            class="btn-force-delete p-1.5 rounded-lg text-xs text-(--color-red)
                            hover:bg-(--color-gray)/20 flex items-center gap-1 cursor-pointer">
                            <i data-lucide="trash" class="size-3.5"></i> ${window.langUi?.Delete ?? "Delete"}
                        </button>
                    </div>
                `
                        : `
                    <span class="text-xs text-(--color-gray)">${item.created_at ?? ""}</span>
                    <div class="flex items-center gap-1">
                        <a href="${route("image_fabrics.edit", item.id)}"
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
            containerId: "#image-fabric-cardgrid",
            filterSelector: "#filter-image-fabric",
            ajax: {
                url: route("image_fabrics.index"),
            },
            renderCard,
            pageLength: 12,
            cardClickRoute: (row) => route("image_fabrics.edit", row.id),
        });
    };

    const bindEvents = () => {
        $(document).on("click", ".btn-delete", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.show(
                trans("langCrud", "delete_confirm_message", {
                    model: modelName,
                }),
                trans("langCrud", "delete_confirm_title"),
                window.langCustomAlert.delete,
                window.langCustomAlert.cancel,
            );
            if (!confirmed) return;
            try {
                await ApiProvider.delete(route("image_fabrics.destroy", id));
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
                trans("langCrud", "restore_confirm_message_short", {
                    model: modelName,
                }),
                trans("langCrud", "restore_confirm_title"),
            );
            if (!confirmed) return;
            await ApiProvider.put(route("image_fabrics.restore", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            cardgrid.reload();
        });

        $(document).on("click", ".btn-force-delete", async function () {
            const id = $(this).data("id");
            const confirmed = await Confirm.show(
                trans("langCrud", "force_delete_confirm_message", {
                    model: modelName,
                }),
                trans("langCrud", "force_delete_confirm_title"),
                window.langUi?.["Force Delete"],
                window.langCustomAlert.cancel,
            );
            if (!confirmed) return;
            await ApiProvider.delete(route("image_fabrics.force-delete", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
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
