import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";

const PageScript = (function () {
    let datatable;
    let form;
    const modelName = window.langModels?.Employee ?? "Employee";

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#employees-datatable",
            filterSelector: "#filter-employees",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("employees.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-employees").val();
                },
            },
            columns: [
                {
                    data: "name",
                    width: "20%",
                },
                {
                    data: "address",
                    width: "20%",
                    className: "text-center",
                },
                {
                    data: "contact",
                    width: "15%",
                    className: "text-center",
                    type: "string",
                },
                {
                    data: "is_active",
                    width: "15%",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-center",
                    render(data, type, row) {
                        const checked = data ? "checked" : "";

                        return `
                            <div class="flex items-center justify-center gap-x-3">
                                <label for="toggle-active-${row.id}" class="relative inline-block w-11 h-6 cursor-pointer">
                                    <input type="checkbox" id="toggle-active-${row.id}" class="peer sr-only toggle-active" data-employee-id="${row.id}" ${checked}>
                                    <span class="absolute inset-0 bg-(--color-dark-gray) rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-(--color-success) peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                                    <span class="absolute top-1/2 inset-s-0.5 -translate-y-1/2 size-5 bg-(--color-light) rounded-full shadow-sm transition-transform duration-200 ease-in-out peer-checked:translate-x-full"></span>
                                </label>
                            </div>
                        `;
                    },
                },
                {
                    data: "notes",
                    width: "25%",
                    render(data) {
                        return `
                            <div class="whitespace-pre-line">
                                ${data ?? "-"}
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

                                        <button type="button" data-id="${id}"
                                            class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                            ${window.langUi?.Delete ?? "Delete"}
                                        </button>
                                        `
                                        : `
                                        <button type="button" data-id="${id}"
                                            class="btn-restore w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-success) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                            ${window.langUi?.Restore ?? "Restore"}
                                        </button>

                                        <button type="button" data-id="${id}"
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

    const openModal = () => {
        HSOverlay.open("#hs-employee-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-employee-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-employee-modal-label").text(title);
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
        setFormMode("create");
        setModalTitle(trans("langCrud", "add_title", { model: modelName }));
    };

    const fillForm = (data) => {
        $("#name").val(data.name ?? "");
        $("#address").val(data.address ?? "");
        $("#contact").val(data.contact ?? "");
        $("#notes").val(data.notes ?? "");
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id = form.dataset.id;

        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("employees.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(route("employees.update", id), payload);
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

    const handleCreate = () => {
        resetModal();
        openModal();
    };

    const handleEdit = async (id) => {
        setModalTitle(trans("langCrud", "edit_title", { model: modelName }));
        setFormMode("edit", id);

        try {
            const response = await ApiProvider.get(route("employees.show", id));
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch employee error:", error);
            Toast.error(
                window.langCustomAlert.error,
                window.langEmployee.fetch_error,
            );
            closeModal();
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "delete_confirm_message", { model: modelName }),
            trans("langCrud", "delete_confirm_title"),
            window.langCustomAlert.delete,
            window.langCustomAlert.cancel,
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("employees.destroy", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Delete employee error:", error);
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

        try {
            await ApiProvider.put(route("employees.restore", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Restore employee error:", error);
        }
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

        try {
            await ApiProvider.delete(route("employees.force-delete", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Force delete employee error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-employee", () => {
            handleCreate();
        });

        $(document).on("click", ".btn-edit", function () {
            handleEdit($(this).data("id"));
        });

        $(document).on("change", ".toggle-active", function () {
            const employeeId = $(this).data("employee-id");
            handleToggleActive(employeeId, this);
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
            .getElementById("hs-employee-modal")
            .addEventListener("close.hs.overlay", () => {
                resetModal();
            });

        $(document).on("click", ".btn-delete", function () {
            handleDelete($(this).data("id"));
        });

        $(document).on("click", ".btn-restore", function () {
            handleRestore($(this).data("id"));
        });

        $(document).on("click", ".btn-force-delete", function () {
            handleForceDelete($(this).data("id"));
        });
    };

    const handleToggleActive = async (userId, checkbox) => {
        const confirmed = await Confirm.show(
            window.langEmployee.toggle_active_confirm_message,
            window.langEmployee.toggle_active_confirm_title,
        );

        if (!confirmed) {
            checkbox.checked = !checkbox.checked;
            return;
        }

        try {
            await ApiProvider.put(route("employees.toggle-active", userId));

            Toast.success(
                window.langCustomAlert.success,
                window.langEmployee.toggle_active_success,
            );
            reloadDatatable();
        } catch (error) {
            checkbox.checked = !checkbox.checked;
            Toast.error(
                window.langCustomAlert.error,
                window.langEmployee.toggle_active_error,
            );
            console.error(error);
        }
    };

    return {
        init() {
            form = document.getElementById("employee-form");

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
