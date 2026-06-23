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
        let payload = {
            name: formData.get("name"),
            permissions: formData.getAll("permissions[]"),
        };

        payload = normalizeFormInputs(form, payload);

        if (mode === "edit" && !payload.password) {
            delete payload.password;
        }

        try {
            if (mode === "create") {
                await ApiProvider.post(route("roles.store"), payload);

                if (action === "save-another") {
                    Toast.success("Success", "User Successfully Created");
                    resetForm();
                    return;
                }

                flashToast("success", "Success", "User Successfully Created");
                window.location.href = route("roles.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("roles.update", id), payload);
                flashToast("success", "Success", "User Successfully Updated");
                window.location.href = route("roles.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    return {
        init() {
            form = document.getElementById("role-form");
            if (!form) return;

            mode = form.dataset.mode;
            id = form.dataset.id;

            bindEvents();
        },
    };
})();

window.permissionManager = function (initial = []) {
    return {
        selected: initial,

        // ===== HELPERS =====
        getAllPermissions() {
            return Array.from(
                document.querySelectorAll('input[name="permissions[]"]'),
            ).map((cb) => cb.value);
        },

        getPermissionsBySubmenu(menuId) {
            return Array.from(
                document.querySelectorAll(
                    `input[name="permissions[]"][data-menu="${menuId}"]`,
                ),
            ).map((cb) => cb.value);
        },

        getPermissionsByGroup(groupId) {
            return Array.from(
                document.querySelectorAll(
                    `input[name="permissions[]"][data-parent="${groupId}"]`,
                ),
            ).map((cb) => cb.value);
        },

        // ===== GLOBAL =====
        toggleAll() {
            const all = this.getAllPermissions();

            if (this.isAllChecked()) {
                this.selected = [];
            } else {
                this.selected = [...new Set(all)];
            }
        },

        isAllChecked() {
            const all = this.getAllPermissions();
            return (
                all.length > 0 && all.every((p) => this.selected.includes(p))
            );
        },

        // ===== GROUP =====
        toggleGroup(groupId) {
            const groupPermissions = this.getPermissionsByGroup(groupId);

            const allSelected = groupPermissions.every((p) =>
                this.selected.includes(p),
            );

            if (allSelected) {
                this.selected = this.selected.filter(
                    (p) => !groupPermissions.includes(p),
                );
            } else {
                this.selected = [
                    ...new Set([...this.selected, ...groupPermissions]),
                ];
            }
        },

        isGroupChecked(groupId) {
            const groupPermissions = this.getPermissionsByGroup(groupId);

            return (
                groupPermissions.length > 0 &&
                groupPermissions.every((p) => this.selected.includes(p))
            );
        },

        // ===== SUBMENU =====
        toggleSubmenu(menuId) {
            const submenuPermissions = this.getPermissionsBySubmenu(menuId);

            const allSelected = submenuPermissions.every((p) =>
                this.selected.includes(p),
            );

            if (allSelected) {
                this.selected = this.selected.filter(
                    (p) => !submenuPermissions.includes(p),
                );
            } else {
                this.selected = [
                    ...new Set([...this.selected, ...submenuPermissions]),
                ];
            }
        },

        isSubmenuChecked(menuId) {
            const submenuPermissions = this.getPermissionsBySubmenu(menuId);

            return (
                submenuPermissions.length > 0 &&
                submenuPermissions.every((p) => this.selected.includes(p))
            );
        },
    };
};

$(function () {
    PageScript.init();
});
