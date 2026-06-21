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
            table: "#price-employees-datatable",
            filterSelector: "#filter-price-employees",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("price_employees.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-price-employees").val();
                },
            },
            columns: [
                {
                    data: "type_fabric.name",
                    className: "text-center",
                    width: "20%",
                },
                {
                    data: "type_color.name",
                    className: "text-center",
                    width: "20%",
                },
                {
                    data: "price_formatted",
                    className: "text-center",
                    width: "20%",
                },
                {
                    data: "notes",
                    width: "15%",
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
        HSOverlay.open("#hs-price-employee-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-price-employee-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-price-employee-modal-label").text(title);
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

        form.querySelectorAll("[data-hs-select]").forEach((el) => {
            const hsSelect = window.HSSelect?.getInstance(el);
            if (hsSelect) hsSelect.setValue("");

            const clearBtn = document.querySelector(
                `[data-clear-select="${el.id}"]`,
            );
            if (clearBtn) clearBtn.style.display = "none";
        });

        setFormMode("create");
        setModalTitle("Add Price Employee");
    };

    const setSelectValue = async (selector, value, apiUrl = null) => {
        const el = document.querySelector(selector);
        if (!el || value === null || value === undefined) return;

        const showClearBtn = () => {
            const clearBtn = document.querySelector(
                `[data-clear-select="${el.id}"]`,
            );
            if (clearBtn) clearBtn.style.display = "";
        };

        if (!apiUrl) {
            el.value = value ?? "";
            $(el).trigger("change");
            const hsSelect = window.HSSelect?.getInstance(el);
            if (hsSelect) {
                hsSelect.setValue(String(value));
                showClearBtn();
            }
            return;
        }

        const hsSelect = window.HSSelect?.getInstance(el);
        if (!hsSelect) return;

        try {
            const res = await fetch(`${apiUrl}?id=${value}`);
            const json = await res.json();
            const item = json.results?.[0];
            if (!item) return;

            const existing = el.querySelector(`option[value="${item.id}"]`);
            if (!existing) {
                const opt = document.createElement("option");
                opt.value = item.id;
                opt.text = item.name;
                el.appendChild(opt);
            }

            el.value = String(item.id);

            el.dispatchEvent(
                new Event("change", {
                    bubbles: true,
                }),
            );

            hsSelect.setValue(String(item.id));

            requestAnimationFrame(() => {
                window.lucide?.createIcons();
            });

            showClearBtn();
        } catch (err) {
            console.error("setSelectValue API error:", err, selector);
        }
    };

    const fillForm = async (data) => {
        await Promise.all([
            setSelectValue(
                "#employee_id",
                data.employee_id,
                route("employees.select"),
            ),
            setSelectValue(
                "#type_fabric_id",
                data.type_fabric_id,
                route("type_fabrics.select"),
            ),
            setSelectValue(
                "#type_color_id",
                data.type_color_id,
                route("type_colors.select"),
            ),
        ]);

        $("#price").val(data.price ?? "");
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
                await ApiProvider.post(route("price_employees.store"), payload);
                Toast.success("Success", "Price Employee Successfully Created");
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("price_employees.update", id),
                    payload,
                );
                Toast.success("Success", "Price Employee Successfully Updated");
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
        setModalTitle("Edit Price Employee");
        setFormMode("edit", id);

        try {
            const response = await ApiProvider.get(
                route("price_employees.show", id),
            );
            await fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch price employee error:", error);
            closeModal();
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "Are you sure you want to delete this price employee? This action cannot be undone.",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("price_employees.destroy", id));
            Toast.success("Success", "Type fabric deleted successfully");
            reloadDatatable();
        } catch (error) {
            console.error("Delete price employee error:", error);
        }
    };

    const handleRestore = async (id) => {
        const confirmed = await Confirm.show(
            "Restore this price employee?",
            "Confirmation",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.put(route("price_employees.restore", id));
            Toast.success("Success", "Type fabric restored");
            reloadDatatable();
        } catch (error) {
            console.error("Restore price employee error:", error);
        }
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.delete(
            "This will permanently delete the price employee. Continue?",
        );

        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("price_employees.force-delete", id));
            Toast.success("Success", "Type fabric permanently deleted");
            reloadDatatable();
        } catch (error) {
            console.error("Force delete price employee error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-price-employee", () => {
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
            .getElementById("hs-price-employee-modal")
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
            form = document.getElementById("price-employee-form");

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
