import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import "@/utils/custom-select";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getDefaultWeekRange } from "@/utils/week";

const formatRupiah = (value) => {
    const raw = String(value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");

    if (!digits) return isNegative ? "-" : "";

    const formatted = Number(digits).toLocaleString("id-ID");

    return isNegative ? `-${formatted}` : formatted;
};

const unformatRupiah = (value) => {
    const raw = String(value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");

    if (!digits) return 0;

    const num = Number(digits);

    return isNegative ? -num : num;
};

const bindRupiahInput = (el) => {
    if (!el) return;

    el.addEventListener("input", () => {
        const cursorAtEnd =
            el.selectionStart === el.value.length &&
            el.selectionEnd === el.value.length;

        el.value = formatRupiah(el.value);

        if (cursorAtEnd) {
            el.setSelectionRange(el.value.length, el.value.length);
        }
    });
};

const handleQuickName = (btn) => {
    const target = document.querySelector(btn.dataset.target);
    if (!target) return;

    target.value = btn.dataset.quickName;
    target.dispatchEvent(new Event("input", { bubbles: true }));
};

const PageScript = (function () {
    let datatable;
    let form;
    let nominalEl;
    let nameEl;
    let isPaidEl;
    const modelName = window.langModels?.Memo ?? "Memo";

    const getDefaultRange = () => getDefaultWeekRange(2);

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const applyDefaultFilter = () => {
        const instance = getFlatpickrInstance("filter-date-range");
        instance.setDate(getDefaultRange(), true);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#memos-datatable",
            filterSelector: "#filter-memos",
            onRowClick: (row) => handleEdit(row.id),
            ajax: {
                url: route("memos.index"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-memos").val() || "active";
                    d.start_week = $("#filter-date-range_start").val() || "";
                    d.end_week = $("#filter-date-range_end").val() || "";
                },
            },
            columns: [
                {
                    data: "employee_name",
                    width: "20%",
                    render(data, type, row) {
                        return row.employee?.name ?? data ?? "-";
                    },
                },
                {
                    data: "name",
                    width: "20%",
                    render(data) {
                        return data ?? "-";
                    },
                },
                {
                    data: "nominal",
                    width: "15%",
                    className: "text-center",
                    render(data) {
                        return `<span>Rp ${Number(data ?? 0).toLocaleString("id-ID")}</span>`;
                    },
                },
                {
                    data: "is_paid",
                    width: "10%",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render(data) {
                        const paidLabel =
                            window.langMemo?.status?.paid ?? "Paid";
                        const unpaidLabel =
                            window.langMemo?.status?.unpaid ?? "Unpaid";

                        if (data) {
                            return `<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                bg-(--color-success)/15 text-(--color-success)">
                                <i data-lucide="check-circle" class="size-3"></i> ${paidLabel}
                            </span>`;
                        }
                        return `<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                            bg-(--color-red)/15 text-(--color-red)">
                            <i data-lucide="clock" class="size-3"></i> ${unpaidLabel}
                        </span>`;
                    },
                },
                {
                    data: "date",
                    width: "15%",
                    className: "text-center",
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

    const openModal = () => HSOverlay.open("#hs-memo-modal");
    const closeModal = () => HSOverlay.close("#hs-memo-modal");

    const setModalTitle = (title) => {
        const labelEl = document.getElementById("hs-memo-modal-label");
        if (labelEl) labelEl.textContent = title;
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

        if (window.HSSelect) {
            const selectEl = document.getElementById("employee_id");
            const instance = selectEl ? HSSelect.getInstance(selectEl) : null;
            instance?.setValue("");
        }

        if (nominalEl) nominalEl.value = "";
        if (nameEl) nameEl.value = "";
        getFlatpickrInstance("date")?.setDate(new Date(), true);
        if (isPaidEl) isPaidEl.checked = false;
    };

    const fillForm = (data) => {
        const empId = data.employee_id;
        const empName = data.employee?.name ?? data.employee_name ?? "";

        if (window.HSSelect) {
            const selectEl = document.getElementById("employee_id");
            const instance = selectEl ? HSSelect.getInstance(selectEl) : null;
            if (instance && empId) {
                const existing = selectEl.querySelector(
                    `option[value="${empId}"]`,
                );
                if (!existing) {
                    const opt = new Option(empName, empId, true, true);
                    selectEl.add(opt);
                }
                instance.setValue(String(empId));
            }
        }

        if (nameEl) nameEl.value = data.name ?? "";
        if (nominalEl) nominalEl.value = formatRupiah(data.nominal ?? 0);
        getFlatpickrInstance("date")?.setDate(data.date ?? new Date(), true);
        if (isPaidEl) isPaidEl.checked = !!data.is_paid;
    };

    const handleCreate = () => {
        resetModal();
        openModal();
    };

    const handleEdit = async (id) => {
        setModalTitle(trans("langCrud", "edit_title", { model: modelName }));
        setFormMode("edit", id);

        try {
            const response = await ApiProvider.get(route("memos.show", id));
            fillForm(response.data);
            openModal();
        } catch (error) {
            console.error("Fetch memo error:", error);
            closeModal();
        }
    };

    const handleDelete = async (id) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "delete_confirm_message", { model: modelName }),
            trans("langCrud", "delete_confirm_title"),
            window.langCustomAlert?.delete,
            window.langCustomAlert?.cancel,
        );
        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("memos.destroy", id));
            Toast.success(
                window.langCustomAlert?.success,
                trans("langCrud", "deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Delete memo error:", error);
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
            await ApiProvider.put(route("memos.restore", id));
            Toast.success(
                window.langCustomAlert?.success,
                trans("langCrud", "restored", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Restore memo error:", error);
        }
    };

    const handleForceDelete = async (id) => {
        const confirmed = await Confirm.show(
            trans("langCrud", "force_delete_confirm_message", {
                model: modelName,
            }),
            trans("langCrud", "force_delete_confirm_title"),
            window.langUi?.["Force Delete"],
            window.langCustomAlert?.cancel,
        );
        if (!confirmed) return;

        try {
            await ApiProvider.delete(route("memos.force-delete", id));
            Toast.success(
                window.langCustomAlert?.success,
                trans("langCrud", "force_deleted", { model: modelName }),
            );
            reloadDatatable();
        } catch (error) {
            console.error("Force delete memo error:", error);
        }
    };

    const submitForm = async (submitter) => {
        const mode = form.dataset.mode;
        const id = form.dataset.id;

        const employeeEl = document.getElementById("employee_id");

        const payload = {
            employee_id: employeeEl?.value || null,
            name: nameEl?.value || "",
            nominal: unformatRupiah(nominalEl?.value),
            date: $("#date_value").val() || "",
            is_paid: isPaidEl?.checked ? 1 : 0,
        };

        try {
            if (mode === "create") {
                await ApiProvider.post(route("memos.store"), payload);
                Toast.success(
                    window.langCustomAlert?.success,
                    trans("langCrud", "created", { model: modelName }),
                );
            }

            if (mode === "edit") {
                await ApiProvider.put(route("memos.update", id), payload);
                Toast.success(
                    window.langCustomAlert?.success,
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

    const bindEvents = () => {
        $(document).on("click", "#btn-create-memo", () => {
            handleCreate();
        });

        $(document).on("click", ".btn-edit", function () {
            handleEdit($(this).data("id"));
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

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;
            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }
            await submitForm(submitter);
        });

        document
            .getElementById("hs-memo-modal")
            ?.addEventListener("close.hs.overlay", () => {
                resetModal();
            });

        $(document).on(
            "flatpickr:range-change",
            "#filter-date-range",
            function (e) {
                if (!e.detail.start || !e.detail.end) return;
                reloadDatatable();
            },
        );

        $(document).on("click", "#btn-reset-filter", () => {
            applyDefaultFilter();
            reloadDatatable();
        });

        $(document).on("mousedown", "[data-quick-name]", function (e) {
            e.preventDefault();
            handleQuickName(this);
        });
    };

    return {
        init() {
            form = document.getElementById("memo-form");
            nominalEl = document.getElementById("nominal");
            nameEl = document.getElementById("name");
            isPaidEl = document.getElementById("is_paid");

            bindRupiahInput(nominalEl);

            applyDefaultFilter();
            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
