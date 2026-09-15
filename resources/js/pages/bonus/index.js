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
    const modelName = window.langModels?.Bonus ?? "Bonus";

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#bonuses-datatable",
            filterSelector: "#filter-bonuses",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("bonuses.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-bonuses").val();
                },
            },
            order: [[0, "asc"]],
            columns: [
                {
                    data: "min_formatted",
                    className: "text-center",
                    width: "25%",
                },
                {
                    data: "bonus_formatted",
                    className: "text-center",
                    width: "25%",
                },
                {
                    data: "notes",
                    width: "40%",
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
                    width: "10%",
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
        HSOverlay.open("#hs-bonus-modal");
    };

    const closeModal = () => {
        HSOverlay.close("#hs-bonus-modal");
    };

    const setModalTitle = (title) => {
        $("#hs-bonus-modal-label").text(title);
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

        const minInput = document.getElementById("min");
        const bonusInput = document.getElementById("bonus");
        if (minInput) RupiahInput.refresh(minInput);
        if (bonusInput) RupiahInput.refresh(bonusInput);

        setFormMode("create");
        setModalTitle(trans("langCrud", "add_title", { model: modelName }));
    };

    const fillForm = (data) => {
        const minInput = document.getElementById("min");
        const bonusInput = document.getElementById("bonus");

        if (minInput) {
            minInput.value = data.min ?? "";
            RupiahInput.refresh(minInput);
        }

        if (bonusInput) {
            bonusInput.value = data.bonus ?? "";
            RupiahInput.refresh(bonusInput);
        }

        $("#notes").val(data.notes ?? "");
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id = form.dataset.id;

        const minInput = document.getElementById("min");
        const bonusInput = document.getElementById("bonus");

        if (minInput) minInput.value = RupiahInput.unformat(minInput.value);
        if (bonusInput) bonusInput.value = RupiahInput.unformat(bonusInput.value);

        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("bonuses.store"), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(route("bonuses.update", id), payload);
                Toast.success(
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
            }

            closeModal();
            reloadDatatable();
        } catch (error) {
        } finally {
            if (minInput) RupiahInput.refresh(minInput);
            if (bonusInput) RupiahInput.refresh(bonusInput);
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
            const response = await ApiProvider.get(route("bonuses.show", id));
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch bonus error:", error);
            Toast.error(
                window.langCustomAlert.error,
                window.langBonus?.fetch_error ?? "Error",
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
            await ApiProvider.delete(route("bonuses.destroy", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Delete bonus error:", error);
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
            await ApiProvider.put(route("bonuses.restore", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Restore bonus error:", error);
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
            await ApiProvider.delete(route("bonuses.force-delete", id));
            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Force delete bonus error:", error);
        }
    };

    const bindEvents = () => {
        $(document).on("click", "#btn-create-bonus", () => {
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
            .getElementById("hs-bonus-modal")
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
            form = document.getElementById("bonus-form");

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
