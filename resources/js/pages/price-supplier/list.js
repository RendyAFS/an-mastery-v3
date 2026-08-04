import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";
import trans from "@/utils/trans";
import Loading from "@/utils/loading";

const PageScript = (function () {
    let datatable;
    let form;
    const modelName = window.langModels?.PriceSupplier ?? "Price Supplier";

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#price-suppliers-datatable",
            filterSelector: "#filter-price-suppliers",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("price_suppliers.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-price-suppliers").val();
                },
            },
            columns: [
                {
                    data: "supplier.name",
                    width: "20%",
                },
                {
                    data: "type_fabric.name",
                    className: "text-center",
                    width: "20%",
                },
                {
                    data: "type_color.name",
                    className: "text-center dt-body-center",
                    width: "20%",
                    render: function (data) {
                        return `${data} ${window.langPriceSupplier?.type_color_suffix ?? ""}`;
                    },
                },
                {
                    data: "price_formatted",
                    className: "text-center",
                    width: "20%",
                },
                {
                    data: "notes",
                    width: "15%",
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
        HSOverlay.open("#hs-price-supplier-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-price-supplier-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-price-supplier-modal-label").text(title);
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
        setModalTitle(trans("langCrud", "add_title", { model: modelName }));
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
                "#supplier_id",
                data.supplier_id,
                route("suppliers.select"),
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

        const priceInput = document.getElementById("price");
        priceInput.value = data.price ?? "";
        RupiahInput.refresh(priceInput);

        $("#notes").val(data.notes ?? "");
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id = form.dataset.id;

        const priceInput = document.getElementById("price");
        if (priceInput) {
            priceInput.value = RupiahInput.unformat(priceInput.value);
        }

        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("price_suppliers.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("price_suppliers.update", id),
                    payload,
                );
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
            }

            closeModal();
            reloadDatatable();
        } catch (error) {
        } finally {
            if (priceInput) RupiahInput.refresh(priceInput);
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

        Loading.start();

        try {
            const response = await ApiProvider.get(
                route("price_suppliers.show", id),
            );
            await fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch price supplier error:", error);
            Toast.error(
                window.langCustomAlert.error,
                window.langPriceSupplier.fetch_error,
            );
            closeModal();
        } finally {
            Loading.stop();
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
            await ApiProvider.delete(route("price_suppliers.destroy", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Delete price supplier error:", error);
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
            await ApiProvider.put(route("price_suppliers.restore", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Restore price supplier error:", error);
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
            await ApiProvider.delete(route("price_suppliers.force-delete", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Force delete price supplier error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-price-supplier", () => {
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
            .getElementById("hs-price-supplier-modal")
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
            form = document.getElementById("price-supplier-form");

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
