import ApiProvider from "@/utils/api-provider";
import FilePondHelper from "@/utils/filepond";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let form, mode, id, pond;

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
    }

    function resetForm() {
        form.reset();
        // Reset hidden fields
        document.getElementById("image_tmp").value = "";
        document.getElementById("remove_image").value = "0";
        if (pond) {
            pond.removeFiles();
        }
    }

    async function submitForm(action, submitter) {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("image-fabrics.store"), payload);

                if (action === "save-another") {
                    Toast.success(
                        "Success",
                        "Image Fabric Successfully Created",
                    );
                    resetForm();
                    return;
                }

                flashToast(
                    "success",
                    "Success",
                    "Image Fabric Successfully Created",
                );
                window.location.href = route("image-fabrics.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("image-fabrics.update", id),
                    payload,
                );
                flashToast(
                    "success",
                    "Success",
                    "Image Fabric Successfully Updated",
                );
                window.location.href = route("image-fabrics.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    function initFilePond() {
        const existingImage = document.getElementById("image-preview")?.value;
        const existingPath = document.getElementById("image-path")?.value;

        pond = FilePondHelper.init({
            selector: 'input[name="image"]',
            uploadUrl: route("filepond.process"),
            deleteUrl: route("filepond.revert"),
            loadUrl: route("filepond.load"),
            existingFileUrl: existingImage || null,
            existingFilePath: existingPath || null,
            acceptedFileTypes: ["image/jpeg", "image/png", "image/webp"],
            allowedMimeTypes: ["image/jpeg", "image/png", "image/webp"],
            maxSize: 2048,
            folder: "tmp",
            multiple: false,
        });

        if (!pond) return;
        bindTmpField(pond);
    }

    function bindTmpField(pond) {
        const hiddenInput = document.getElementById("image_tmp");
        const removeInput = document.getElementById("remove_image");

        pond.on("processfile", (error, file) => {
            if (!error) {
                hiddenInput.value = file.serverId;
                if (removeInput) removeInput.value = "0";
            }
        });

        pond.on("removefile", () => {
            hiddenInput.value = "";
            if (removeInput) removeInput.value = "1";
        });
    }

    return {
        init() {
            form = document.getElementById("image-fabric-form");
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
