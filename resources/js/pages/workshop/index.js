import ApiProvider from "@/utils/api-provider";
import initCardgrid from "@/utils/cardgrid";
import FilePondHelper from "@/utils/filepond";
import openCameraCapture from "@/utils/camera-capture";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";
import Loading from "@/utils/loading";
import { initLucide } from "@/utils/lucide";

const PageScript = (function () {
    let cardgrid;
    let form, pond;
    let removedImageIds = [];
    let currentImages = [];
    let currentIndex = 0;
    const modelName = window.langModels?.Workshop ?? "Workshop";

    const openModal = () => {
        HSOverlay.open("#hs-workshop-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-workshop-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-workshop-modal-label").text(title);
    };

    const setFormMode = (mode, id = null) => {
        form.dataset.mode = mode;
        if (id) {
            form.dataset.id = id;
        } else {
            delete form.dataset.id;
        }
    };

    const resetModal = () => {
        form.reset();
        document.getElementById("images_tmp").value = "[]";
        document.getElementById("removed_images").value = "[]";
        removedImageIds = [];
        $("#existing-images").empty();
        if (pond) {
            pond.removeFiles();
        }

        const isActive = document.getElementById("is_active");
        if (isActive) isActive.checked = true;

        setFormMode("create");
        setModalTitle(trans("langCrud", "add_title", { model: modelName }));
    };

    const fillForm = (data) => {
        const nameInput = document.getElementById("name");
        const locationInput = document.getElementById("location");
        const isActive = document.getElementById("is_active");

        if (nameInput) nameInput.value = data.name ?? "";
        if (locationInput) locationInput.value = data.location ?? "";
        if (isActive) isActive.checked = !!data.is_active;

        $("#images_tmp").val("[]");
        $("#removed_images").val("[]");
        removedImageIds = [];
        if (pond) {
            pond.removeFiles();
        }

        const existingContainer = $("#existing-images");
        existingContainer.empty();

        if (data.images && data.images.length > 0) {
            data.images.forEach((img) => {
                const imgHtml = `
                    <div class="relative" data-media-id="${img.id}">
                        <img src="${img.url}" alt="${data.name}"
                            class="w-full aspect-square object-cover rounded-lg border border-(--color-gray)">
                        <button type="button" data-remove-existing="${img.id}"
                            class="absolute -top-2 -right-2 size-6 flex items-center justify-center rounded-full
                                bg-(--color-danger) text-white shadow cursor-pointer">
                            <i data-lucide="x" class="size-3.5"></i>
                        </button>
                    </div>
                `;
                existingContainer.append(imgHtml);
            });
            initLucide();
        }
    };

    const handleCreate = () => {
        resetModal();
        openModal();
    };

    const handleEdit = async (id) => {
        setModalTitle(trans("langCrud", "edit_title", { model: modelName }));
        setFormMode("edit", id);

        Loading.start();

        try {
            const response = await ApiProvider.get(route("workshops.show", id));
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch workshop error:", error);
            closeModal();
        } finally {
            Loading.stop();
        }
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id = form.dataset.id;

        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            payload.images_tmp = JSON.parse(payload.images_tmp || "[]");
        } catch (e) {
            payload.images_tmp = [];
        }

        payload.removed_images = removedImageIds;

        try {
            if (mode === "create") {
                await ApiProvider.post(route("workshops.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(route("workshops.update", id), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
            }

            closeModal();
            cardgrid.reload();
        } catch (error) {
            // Error handled by ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const images = item.images?.length
            ? item.images
            : item.image_url
              ? [{ url: item.image_url }]
              : [];
        const imageUrl = images[0]?.url;
        const imageCount = images.length;

        return `
        <div class="h-84 bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4 flex flex-col gap-3
            ${isDeleted ? "opacity-60 border border-dashed border-(--color-red)/40" : ""}">

            <div class="relative aspect-video rounded-lg bg-(--color-gray)/20 dark:bg-(--color-dark-gray)/20
                flex items-center justify-center overflow-hidden"
                ${imageUrl ? `data-view-images='${JSON.stringify(images.map((i) => i.url))}' data-no-card-click class="cursor-pointer"` : ""}>
                ${
                    imageUrl
                        ? `<img src="${imageUrl}" alt="${item.name}" class="w-full h-full object-cover rounded-lg cursor-pointer">`
                        : `<i data-lucide="building-2" class="size-10 text-(--color-gray)"></i>`
                }
                ${
                    imageCount > 1
                        ? `<span class="absolute bottom-1.5 right-1.5 text-[11px] font-semibold px-1.5 py-0.5 rounded-full bg-black/60 text-white flex items-center gap-1">
                            <i data-lucide="images" class="size-4"></i> ${imageCount}
                        </span>`
                        : ""
                }
                <div class="absolute top-2 right-2">
                    <span class="badge ${item.is_active ? "badge-success" : "badge-danger"} text-[11px]">
                        ${item.is_active ? (window.langUi?.Active ?? "Active") : (window.langUi?.Inactive ?? "Inactive")}
                    </span>
                </div>
            </div>

            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm truncate text-(--color-dark) dark:text-(--color-light)">${item.name}</p>
                <p class="text-xs text-(--color-dark-gray) mt-1 flex items-start gap-1 line-clamp-2">
                    <i data-lucide="map-pin" class="size-3.5 shrink-0 mt-0.5 text-(--color-primary)"></i>
                    <span>${item.location || "-"}</span>
                </p>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-(--color-gray)/20">
                ${
                    isDeleted
                        ? `
                    <span class="text-xs text-(--color-dark-gray) font-medium flex items-center gap-1">
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
                    <span class="text-xs text-(--color-dark-gray)">${item.created_at ?? ""}</span>
                    <div class="flex items-center gap-1">
                        <button data-id="${item.id}"
                            class="btn-edit p-1.5 rounded-lg hover:bg-(--color-gray)/20
                            text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                            <i data-lucide="square-pen" class="size-4"></i>
                        </button>
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
            containerId: "#workshop-cardgrid",
            filterSelector: "#filter-workshop",
            ajax: {
                url: route("workshops.index"),
            },
            renderCard,
            pageLength: 12,
        });
    };

    const renderViewer = () => {
        $("#viewer-image").attr("src", currentImages[currentIndex] ?? "");
        $("#viewer-counter").text(
            currentImages.length > 1
                ? `${currentIndex + 1} / ${currentImages.length}`
                : "",
        );
        $("#viewer-prev, #viewer-next").toggleClass(
            "hidden",
            currentImages.length <= 1,
        );
    };

    const openViewer = (images, startIndex = 0) => {
        currentImages = images;
        currentIndex = startIndex;
        renderViewer();
        HSOverlay.open("#hs-workshop-viewer");
        initLucide();
    };

    function initFilePond() {
        pond = FilePondHelper.init({
            selector: "#workshop-filepond",
            uploadUrl: route("filepond.process"),
            deleteUrl: route("filepond.revert"),
            acceptedFileTypes: ["image/jpeg", "image/png", "image/webp"],
            allowedMimeTypes: ["image/jpeg", "image/png", "image/webp"],
            maxSize: 2048,
            folder: "tmp",
            multiple: true,
        });

        if (!pond) return;
        bindTmpField(pond);
        bindCameraButton(pond);
    }

    function bindTmpField(pond) {
        const hiddenInput = document.getElementById("images_tmp");

        const syncTmp = () => {
            const ids = pond
                .getFiles()
                .filter((f) => f.serverId)
                .map((f) => f.serverId);
            hiddenInput.value = JSON.stringify(ids);
        };

        pond.on("processfile", syncTmp);
        pond.on("removefile", syncTmp);
    }

    function bindCameraButton(pond) {
        const btn = document.getElementById("camera-btn");
        if (!btn) return;

        btn.addEventListener("click", () => {
            openCameraCapture({
                onCapture: (file) => {
                    pond.addFile(file);
                },
            });
        });
    }

    const bindEvents = () => {
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitter = e.submitter;
                if (submitter?.hasAttribute("data-button-loading")) {
                    startLoading(submitter);
                }
                await submitForm(submitter);
            });
        }

        $(document).on("click", "#btn-create-workshop", function () {
            handleCreate();
        });

        $(document).on("click", ".btn-edit", function (e) {
            e.stopPropagation();
            const id = $(this).data("id");
            handleEdit(id);
        });

        $(document).on("click", "[data-remove-existing]", function () {
            const mediaId = $(this).data("remove-existing");
            removedImageIds.push(mediaId);
            $(this).closest("[data-media-id]").remove();
        });

        $(document).on("click", "[data-view-images]", function (e) {
            e.stopPropagation();
            e.preventDefault();
            const images = JSON.parse($(this).attr("data-view-images"));
            openViewer(images, 0);
        });

        $(document).on("click", "#viewer-prev", function () {
            currentIndex =
                (currentIndex - 1 + currentImages.length) %
                currentImages.length;
            renderViewer();
        });

        $(document).on("click", "#viewer-next", function () {
            currentIndex = (currentIndex + 1) % currentImages.length;
            renderViewer();
        });

        $(document).on("click", ".btn-delete", async function (e) {
            e.stopPropagation();
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
                await ApiProvider.delete(route("workshops.destroy", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "deleted", { model: modelName }),
                );
                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });

        $(document).on("click", ".btn-restore", async function (e) {
            e.stopPropagation();
            const id = $(this).data("id");
            const confirmed = await Confirm.show(
                trans("langCrud", "restore_confirm_message_short", {
                    model: modelName,
                }),
                trans("langCrud", "restore_confirm_title"),
            );
            if (!confirmed) return;
            try {
                await ApiProvider.put(route("workshops.restore", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "restored", { model: modelName }),
                );
                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });

        $(document).on("click", ".btn-force-delete", async function (e) {
            e.stopPropagation();
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
            try {
                await ApiProvider.delete(route("workshops.force-delete", id));
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "force_deleted", { model: modelName }),
                );
                cardgrid.reload();
            } catch (err) {
                console.error(err);
            }
        });
    };

    return {
        init() {
            form = document.getElementById("workshop-form");
            CardGrid();
            bindEvents();
            initFilePond();
        },
    };
})();

$(function () {
    PageScript.init();
});
