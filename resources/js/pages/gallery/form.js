import ApiProvider from "@/utils/api-provider";
import FilePondHelper from "@/utils/filepond";
import openCameraCapture from "@/utils/camera-capture";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";

const PageScript = (function () {
    let form, mode, id, pond;
    let removedImageIds = [];
    const modelName = window.langModels?.Gallery ?? "Gallery";

    function bindEvents() {
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitter = e.submitter;
            const action = submitter?.dataset.action ?? "save";

            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }

            await submitForm(action, submitter);
        });

        $(document).on("click", "[data-remove-existing]", function () {
            const mediaId = $(this).data("remove-existing");
            removedImageIds.push(mediaId);
            $(this).closest("[data-media-id]").remove();
        });
    }

    function resetForm() {
        form.reset();
        document.getElementById("images_tmp").value = "[]";
        document.getElementById("removed_images").value = "[]";
        removedImageIds = [];
        $("#existing-images").empty();
        if (pond) {
            pond.removeFiles();
        }
    }

    async function submitForm(action, submitter) {
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
                await ApiProvider.post(route("galleries.store"), payload);

                const message = trans("langCrud", "created", {
                    model: modelName,
                });

                if (action === "save-another") {
                    Toast.success(window.langCustomAlert.success, message);
                    resetForm();
                    return;
                }

                flashToast("success", window.langCustomAlert.success, message);
                window.location.href = route("galleries.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("galleries.update", id), payload);
                flashToast(
                    "success",
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
                window.location.href = route("galleries.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    function initFilePond() {
        pond = FilePondHelper.init({
            selector: 'input[name="images"]',
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

    return {
        init() {
            form = document.getElementById("gallery-form");
            if (!form) return;

            mode = form.dataset.mode;
            id = form.dataset.id;

            bindEvents();
            initFilePond();
        },
    };
})();

$(function () {
    PageScript.init();
});
