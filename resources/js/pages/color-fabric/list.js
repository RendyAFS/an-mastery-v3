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
            table: "#color-fabrics-datatable",
            filterSelector: "#filter-color-fabrics",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("color_fabrics.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-color-fabrics").val();
                },
            },
            columns: [
                {
                    data: "name",
                    width: "30%",
                },
                {
                    data: "code_color",
                    width: "30%",
                    className: "text-center",
                    render(data) {
                        return `
                            <div class="flex items-center justify-center gap-3">
                                <div
                                    class="size-6 rounded-full border border-(--color-gray) shadow-sm"
                                    style="background-color: ${data}">
                                </div>

                                <span class="font-medium">
                                    ${data}
                                </span>
                            </div>
                        `;
                    },
                },
                {
                    data: "notes",
                    width: "35%",
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
        HSOverlay.open("#hs-color-fabric-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-color-fabric-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-color-fabric-modal-label").text(title);
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
        $("#color_picker").val("#000000");
        $("#code_color").val("#000000");
        setFormMode("create");
        setModalTitle("Add Color Fabric");
    };

    const fillForm = (data) => {
        $("#name").val(data.name ?? "");
        $("#code_color").val(data.code_color ?? "#000000");
        $("#color_picker").val(data.code_color ?? "#000000");
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
                await ApiProvider.post(route("color_fabrics.store"), payload);
                Toast.success("Success", "Color Fabric Successfully Created");
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("color_fabrics.update", id),
                    payload,
                );
                Toast.success("Success", "Color Fabric Successfully Updated");
            }

            closeModal();
            reloadDatatable();
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const initColorPicker = () => {
        const colorPicker = document.getElementById("color_picker");
        const codeColor = document.getElementById("code_color");

        if (!colorPicker || !codeColor) return;

        colorPicker.addEventListener("input", function () {
            codeColor.value = this.value;
        });
    };

    const handleCreate = () => {
        resetModal();
        openModal();
    };

    const handleEdit = async (id) => {
        setModalTitle("Edit Color Fabric");
        setFormMode("edit", id);

        try {
            const response = await ApiProvider.get(
                route("color_fabrics.show", id),
            );
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch color fabric error:", error);
            closeModal();
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this color fabric? This action cannot be undone.",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("color_fabrics.destroy", id));
            Toast.success("Success", "Color fabric deleted successfully");
            reloadDatatable();
        } catch (error) {
            console.error("Delete color fabric error:", error);
        }
    };

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            "Restore this color fabric?",
            "Confirmation",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.put(route("color_fabrics.restore", id));
            Toast.success("Success", "Color fabric restored");
            reloadDatatable();
        } catch (error) {
            console.error("Restore color fabric error:", error);
        }
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "This will permanently delete the color fabric. Continue?",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("color_fabrics.force-delete", id));
            Toast.success("Success", "Color fabric permanently deleted");
            reloadDatatable();
        } catch (error) {
            console.error("Force delete color fabric error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-color-fabric", () => {
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
            .getElementById("hs-color-fabric-modal")
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
            form = document.getElementById("color-fabric-form");
            initColorPicker();
            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
