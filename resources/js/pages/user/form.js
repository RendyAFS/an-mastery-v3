import ApiProvider from "@/utils/api-provider";

const PageScript = (function () {
    let form;
    let mode;
    let id;
    let submitAction = "save";

    function bindEvents() {
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            await submitForm(submitAction);
            submitAction = "save";
        });

        const actionButtons = form.querySelectorAll("[data-action]");
        actionButtons.forEach((btn) => {
            btn.addEventListener("click", () => {
                submitAction = btn.dataset.action;
            });
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

                Toast.success("User berhasil dibuat");

                if (action === "save-another") {
                    form.reset();
                    return;
                }

                window.location.href = route("users.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("users.update", id), payload);

                Toast.success("User berhasil diperbarui");
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
