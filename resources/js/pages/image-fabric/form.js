import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let form, mode, id;

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
    }

    async function submitForm(action, submitter) {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(form, payload);

        if (mode === "edit" && !payload.password) {
            delete payload.password;
        }

        try {
            if (mode === "create") {
                await ApiProvider.post(route("image-fabrics.store"), payload);

                if (action === "save-another") {
                    Toast.success("Success", "Image Fabric Successfully Created");
                    resetForm();
                    return;
                }

                flashToast("success", "Success", "Image Fabric Successfully Created");
                window.location.href = route("image-fabrics.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("image-fabrics.update", id), payload);
                flashToast("success", "Success", "Image Fabric Successfully Updated");
                window.location.href = route("image-fabrics.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    return {
        init() {
            form = document.getElementById("image-fabric-form");
            if (!form) return;

            mode = form.dataset.mode;
            id = form.dataset.id;

            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
