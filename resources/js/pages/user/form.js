import ApiProvider from "@/utils/api-provider";
import Toast from "@/utils/custom-toast";

const PageScript = (function () {
    let form;
    let mode;
    let id;

    function bindEvents() {
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const action = e.submitter?.dataset.action ?? "save";
            await submitForm(action);
        });
    }

    async function submitForm(action) {
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        if (mode === "edit" && !payload.password) {
            delete payload.password;
        }

        try {
            if (mode === "create") {
                await ApiProvider.post(route("users.store"), payload);
                if (action === "save-another") {
                    flashToast("success", "Success", "User Successfully Created");
                    window.location.reload();
                    return;
                }
                flashToast("success", "Success", "User Successfully Created");
                window.location.href = route("users.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("users.update", id), payload);
                flashToast("success", "Success", "User Successfully Updated");
                window.location.href = route("users.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        }
    }

    return {
        init() {
            form = document.getElementById("user-form");
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
