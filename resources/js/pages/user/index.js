import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";
import Loading from "@/utils/loading";

const PageScript = (function () {
    let datatable;
    let form;
    const modelName = window.langModels?.User ?? "User";

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    // ------------------------------------------------------------------ Modal
    const openModal = () => {
        HSOverlay.open("#hs-user-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-user-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-user-modal-label").text(title);
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

        // Reset HSSelect dropdowns
        form.querySelectorAll("select[data-hs-select]").forEach((select) => {
            const instance = HSSelect.getInstance(select);
            instance?.setValue("");

            const clearBtn = document.querySelector(
                `[data-clear-select="${select.id}"]`,
            );
            if (clearBtn) clearBtn.style.display = "none";
        });

        // Reset checkbox
        const isActive = document.getElementById("is_active");
        if (isActive) isActive.checked = false;

        // Sembunyikan hint password
        const pwHint = document.getElementById("password-hint");
        const pwConfirmHint = document.getElementById("password-confirm-hint");
        if (pwHint) pwHint.classList.add("hidden");
        if (pwConfirmHint) pwConfirmHint.classList.add("hidden");

        setFormMode("create");
        setModalTitle(trans("langCrud", "add_title", { model: modelName }));
    };

    const fillForm = (data) => {
        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const isActive = document.getElementById("is_active");

        if (nameInput) nameInput.value = data.name ?? "";
        if (emailInput) emailInput.value = data.email ?? "";
        if (isActive) isActive.checked = !!data.is_active;

        // Isi dropdown roles
        const rolesSelect = document.getElementById("roles");
        if (rolesSelect && data.role_id) {
            const instance = HSSelect.getInstance(rolesSelect);
            instance?.setValue(String(data.role_id));

            // Tampilkan tombol clear jika ada
            const clearBtn = document.querySelector('[data-clear-select="roles"]');
            if (clearBtn) clearBtn.style.display = "";
        }

        // Tampilkan hint password saat mode edit
        const pwHint = document.getElementById("password-hint");
        const pwConfirmHint = document.getElementById("password-confirm-hint");
        if (pwHint) pwHint.classList.remove("hidden");
        if (pwConfirmHint) pwConfirmHint.classList.remove("hidden");
    };

    // ------------------------------------------------------------------ CRUD
    const handleCreate = () => {
        resetModal();
        openModal();
    };

    const handleEdit = async (id) => {
        setModalTitle(trans("langCrud", "edit_title", { model: modelName }));
        setFormMode("edit", id);

        Loading.start();

        try {
            const response = await ApiProvider.get(route("users.show", id));
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch user error:", error);
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

        // Hapus password kosong saat edit
        if (mode === "edit" && !payload.password) {
            delete payload.password;
            delete payload.password_confirmation;
        }

        try {
            if (mode === "create") {
                await ApiProvider.post(route("users.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(route("users.update", id), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
            }

            closeModal();
            reloadDatatable();
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const handleDelete = async (userId) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "delete_confirm_message", { model: modelName }),
            trans("langCrud", "delete_confirm_title"),
            window.langCustomAlert.delete,
            window.langCustomAlert.cancel,
        );

        if (!confirmed) {
            return;
        }

        try {
            await ApiProvider.delete(route("users.destroy", userId));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "deleted", { model: modelName }),
            );

            reloadDatatable();
        } catch (error) {
            console.error("Delete user error:", error);
        }
    };

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "restore_confirm_message_short", {
                model: modelName,
            }),
            trans("langCrud", "restore_confirm_title"),
        );

        if (!confirmed) return;

        await ApiProvider.put(route("users.restore", id));

        Toast.success(
            window.langCustomAlert.success,
            trans("langCrud", "restored", { model: modelName }),
        );
        reloadDatatable();
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "force_delete_confirm_message", {
                model: modelName,
            }),
            trans("langCrud", "force_delete_confirm_title"),
            window.langUi?.["Force Delete"],
            window.langCustomAlert.cancel,
        );

        if (!confirmed) return;

        await ApiProvider.delete(route("users.force-delete", id));

        Toast.success(
            window.langCustomAlert.success,
            trans("langCrud", "force_deleted", { model: modelName }),
        );
        reloadDatatable();
    };

    const handleToggleActive = async (userId, checkbox) => {
        const confirmed = await Confirm.show(
            window.langUser.toggle_active_confirm_message,
            window.langUser.toggle_active_confirm_title,
        );

        if (!confirmed) {
            checkbox.checked = !checkbox.checked;
            return;
        }

        try {
            await ApiProvider.put(route("users.toggle-active", userId));

            Toast.success(
                window.langCustomAlert.success,
                window.langUser.toggle_active_success,
            );
            reloadDatatable();
        } catch (error) {
            checkbox.checked = !checkbox.checked;
            Toast.error(
                window.langCustomAlert.error,
                window.langUser.toggle_active_error,
            );
            console.error(error);
        }
    };

    // ------------------------------------------------------------------ DataTable
    const DataTable = () => {
        datatable = initDatatable({
            table: "#users-datatable",
            filterSelector: "#filter-users",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("users.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-users").val();
                },
            },
            columns: [
                {
                    data: "name",
                    width: "25%",
                },
                {
                    data: "email",
                    width: "25%",
                },
                {
                    data: "roles",
                    width: "25%",
                    className: "text-center",
                    render(data) {
                        if (!Array.isArray(data)) return "";

                        return `
                            <div class="inline-flex flex-wrap gap-2">
                                ${data
                                    .map((role) => renderRoleBadge(role.name))
                                    .join("")}
                            </div>
                        `;
                    },
                },
                {
                    data: "is_active",
                    width: "20%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(data, type, row) {
                        const checked = data ? "checked" : "";

                        return `
                            <div class="flex items-center justify-center gap-x-3">
                                <label for="toggle-active-${row.id}" class="relative inline-block w-11 h-6 cursor-pointer">
                                    <input type="checkbox" id="toggle-active-${row.id}" class="peer sr-only toggle-active" data-user-id="${row.id}" ${checked}>
                                    <span class="absolute inset-0 bg-(--color-dark-gray) rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-(--color-success) peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                                    <span class="absolute top-1/2 inset-s-0.5 -translate-y-1/2 size-5 bg-(--color-light) rounded-full shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-full"></span>
                                </label>
                            </div>
                        `;
                    },
                },
                {
                    data: "id",
                    width: "5%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(id, type, row) {
                        const isDeleted = row.deleted_at !== null;

                        return `
                        <div class="hs-dropdown [--auto-close:inside] relative inline-flex">
                            <button type="button" class="hs-dropdown-toggle inline-flex items-center gap-x-3 px-3 py-2
                                text-sm font-medium rounded-lg
                                bg-(--color-gray)/20 dark:bg-(--color-dark-gray)/20
                                hover:bg-(--color-gray)/40
                                cursor-pointer">
                                <i data-lucide="ellipsis-vertical" class="size-4"></i>
                            </button>

                            <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 mt-2 hidden z-10
                                transition-[margin,opacity] opacity-0 duration-300
                                w-auto bg-(--color-light) dark:bg-(--color-dark) dark:border dark:border-(--color-gray)/30
                                shadow-md rounded-lg p-2"
                                role="menu" aria-orientation="vertical">

                                <div class="p-1 space-y-0.5">

                                ${
                                    !isDeleted
                                        ? `
                                        <button type="button" data-id="${id}"
                                            class="btn-edit w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="square-pen" class="size-4"></i>
                                            ${window.langUi?.Edit ?? "Edit"}
                                        </button>

                                        <button type="button" data-user-id="${id}"
                                            class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                            ${window.langUi?.Delete ?? "Delete"}
                                        </button>
                                        `
                                        : `
                                        <button type="button" data-user-id="${id}"
                                            class="btn-restore w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-success) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                            ${window.langUi?.Restore ?? "Restore"}
                                        </button>

                                        <button type="button" data-user-id="${id}"
                                            class="btn-force-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash" class="size-4"></i>
                                            ${window.langUi?.["Force Delete"] ?? "Force Delete"}
                                        </button>
                                        `
                                }
                                </div>
                            </div>
                        </div>
                        `;
                    },
                },
            ],
        });
    };

    // ------------------------------------------------------------------ Helpers
    function renderRoleBadge(roleName) {
        const map = {
            "Super Admin": "badge-danger",
            Admin: "badge-warning",
        };

        const classes = map[roleName] ?? "badge-success";

        return `
            <span class="badge ${classes}">
                ${roleName}
            </span>
        `;
    }

    // ------------------------------------------------------------------ Events
    const bindEvents = () => {
        $(document).on("click", "#btn-create-user", () => {
            handleCreate();
        });

        $(document).on("click", ".btn-edit", function () {
            handleEdit($(this).data("id"));
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitter = e.submitter;

            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }

            await submitForm(submitter);
        });

        document
            .getElementById("hs-user-modal")
            .addEventListener("close.hs.overlay", () => {
                resetModal();
            });

        $(document).on("click", ".btn-delete", function (e) {
            e.preventDefault();
            const userId = $(this).data("user-id");
            handleDelete(userId);
        });

        $(document).on("change", ".toggle-active", function () {
            const userId = $(this).data("user-id");
            handleToggleActive(userId, this);
        });

        $(document).on("click", ".btn-restore", function () {
            const id = $(this).data("user-id");
            handleRestore(id);
        });

        $(document).on("click", ".btn-force-delete", function () {
            const id = $(this).data("user-id");
            handleForceDelete(id);
        });
    };

    return {
        init() {
            form = document.getElementById("user-form");

            DataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
