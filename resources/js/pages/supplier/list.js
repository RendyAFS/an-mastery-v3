import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let datatable;
    let form;

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#suppliers-datatable",
            filterSelector: "#filter-suppliers",
            ajax: {
                url: route("suppliers.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-suppliers").val();
                },
            },
            columns: [
                {
                    data: "name",
                    width: "25%",
                },
                {
                    data: "address",
                    width: "25%",
                    className: "text-center",
                },
                {
                    data: "contact",
                    width: "20%",
                    className: "text-center",
                    type: "string",
                },
                {
                    data: "notes",
                    width: "25%",
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
                                            Edit
                                        </button>

                                        <button type="button" data-id="${id}"
                                            class="btn-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                            Delete
                                        </button>
                                        `
                                        : `
                                        <button type="button" data-id="${id}"
                                            class="btn-restore w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-success) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="rotate-ccw" class="size-4"></i>
                                            Restore
                                        </button>

                                        <button type="button" data-id="${id}"
                                            class="btn-force-delete w-full flex items-center gap-x-2 py-2 px-2 rounded-lg text-sm
                                            text-(--color-red) hover:bg-(--color-gray)/20
                                            focus:outline-hidden focus:bg-dropdown-item-focus cursor-pointer">
                                            <i data-lucide="trash" class="size-4"></i>
                                            Force Delete
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
        HSOverlay.open("#hs-supplier-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-supplier-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-supplier-modal-label").text(title);
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
        setModalTitle("Add Supplier");
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
                await ApiProvider.post(route("suppliers.store"), payload);
                Toast.success("Success", "Supplier Successfully Created");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("suppliers.update", id), payload);
                Toast.success("Success", "Supplier Successfully Updated");
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
        setModalTitle("Edit Supplier");
        setFormMode("edit", id);
        openModal();

        try {
            const response = await ApiProvider.get(route("suppliers.show", id));
            fillForm(response.data);
        } catch (error) {
            console.error("Fetch supplier error:", error);
            closeModal();
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this supplier? This action cannot be undone.",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("suppliers.destroy", id));
            Toast.success("Success", "Supplier deleted successfully");
            reloadDatatable();
        } catch (error) {
            console.error("Delete supplier error:", error);
        }
    };

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            "Restore this supplier?",
            "Confirmation",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.put(route("suppliers.restore", id));
            Toast.success("Success", "Supplier restored");
            reloadDatatable();
        } catch (error) {
            console.error("Restore supplier error:", error);
        }
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "This will permanently delete the supplier. Continue?",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("suppliers.force-delete", id));
            Toast.success("Success", "Supplier permanently deleted");
            reloadDatatable();
        } catch (error) {
            console.error("Force delete supplier error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-supplier", () => {
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
            .getElementById("hs-supplier-modal")
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

    return {
        init() {
            form = document.getElementById("supplier-form");

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
