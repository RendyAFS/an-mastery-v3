import ApiProvider from "@/utils/api-provider";
import FilePondHelper from "@/utils/filepond";
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

        formData.append("_method", "PUT");

        try {
            await ApiProvider.post(route("profile.update"), formData);
            flashToast("success", "Success", "Profile successfully updated");
            window.location.reload();
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

    function initFilePond() {
        const existingImage = document.getElementById("avatar-preview")?.value;

        const pond = FilePondHelper.init({
            selector: 'input[name="avatar"]',
            uploadUrl: route("filepond.process"),
            deleteUrl: route("filepond.revert"),
            existingFileUrl: existingImage,
            acceptedFileTypes: ["image/*"],
            multiple: false,
        });

        if (!pond) return;

        pond.on("processfile", (error, file) => {
            if (!error) {
                document.getElementById("avatar_tmp").value = file.serverId;
            }
        });

        pond.on("removefile", () => {
            document.getElementById("avatar_tmp").value = "";
        });
    }

    return {
        init() {
            profileForm = document.getElementById("profile-form");
            passwordForm = document.getElementById("password-form");

            if (!profileForm && !passwordForm) return;

            bindEvents();

            if (profileForm) {
                initFilePond();
            }
        },
    };
})();

$(function () {
    PageScript.init();
});
