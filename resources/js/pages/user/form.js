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

        bindDeleteButtons();
    }

    function bindDeleteButtons() {
        // Untuk tombol delete di index page (jika ada)
        document.addEventListener("click", async (e) => {
            const deleteBtn = e.target.closest("[data-delete-user]");
            if (!deleteBtn) return;

            e.preventDefault();

            const userId = deleteBtn.dataset.deleteUser;
            const userName = deleteBtn.dataset.userName || "this user";

            await handleDelete(userId, userName, deleteBtn);
        });
    }

    async function handleDelete(userId, userName, button) {
        // Tampilkan confirm modal
        const confirmed = await Confirm.delete(
            `Are you sure you want to delete <strong>${userName}</strong>? This action cannot be undone.`,
        );

        if (!confirmed) return;

        // Start loading state jika tombol punya data-button-loading
        if (button?.hasAttribute("data-button-loading")) {
            startLoading(button);
        }

        try {
            await ApiProvider.delete(route("users.destroy", userId));

            Toast.success("Success", "User deleted successfully");

            // Tunggu sebentar agar toast terlihat, lalu reload atau redirect
            setTimeout(() => {
                window.location.reload();
                // Atau jika ingin redirect:
                // window.location.href = route("users.index");
            }, 1000);
        } catch (error) {
            // Error sudah ditangani oleh ApiProvider
            // Tapi kita bisa tambahkan handling khusus jika perlu
        } finally {
            if (button?.hasAttribute("data-button-loading")) {
                stopLoading(button);
            }
        }
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
                await ApiProvider.post(route("users.store"), payload);

                if (action === "save-another") {
                    Toast.success("Success", "User Successfully Created");
                    resetForm();
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
        } finally {
            stopLoading(submitter);
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
