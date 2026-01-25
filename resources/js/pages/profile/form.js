import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let profileForm, passwordForm;

    function bindEvents() {
        if (profileForm) {
            profileForm.addEventListener("submit", async (e) => {
                e.preventDefault();

                const submitter = e.submitter;

                if (submitter?.hasAttribute("data-button-loading")) {
                    startLoading(submitter);
                }

                await submitProfileForm(submitter);
            });
        }

        if (passwordForm) {
            passwordForm.addEventListener("submit", async (e) => {
                e.preventDefault();

                const submitter = e.submitter;

                if (submitter?.hasAttribute("data-button-loading")) {
                    startLoading(submitter);
                }

                await submitPasswordForm(submitter);
            });
        }
    }

    async function submitProfileForm(submitter) {
        const formData = new FormData(profileForm);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(profileForm, payload);

        try {
            await ApiProvider.put(route("profile.update"), payload);
            Toast.success("Success", "Profile successfully updated");
        } catch (error) {
            // error ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    async function submitPasswordForm(submitter) {
        const formData = new FormData(passwordForm);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(passwordForm, payload);

        try {
            await ApiProvider.put(route("profile.update-password"), payload);
            Toast.success("Success", "Password successfully updated");
            passwordForm.reset();
        } catch (error) {
            // error ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    return {
        init() {
            profileForm = document.getElementById("profile-form");
            passwordForm = document.getElementById("password-form");

            if (!profileForm && !passwordForm) return;

            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
