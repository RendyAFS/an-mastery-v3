import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import { initLucide } from "@/utils/lucide";
import trans from "@/utils/trans";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";

const formatSignedRupiah = (value) => {
    const raw = String(value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");
    if (!digits) return isNegative ? "-" : "";
    const formatted = Number(digits).toLocaleString("id-ID");
    return isNegative ? `-${formatted}` : formatted;
};

const unformatSignedRupiah = (el) => {
    if (!el) return 0;
    const raw = String(el.value ?? "").replace(/[^0-9-]/g, "");
    const isNegative = raw.startsWith("-");
    const digits = raw.replace(/-/g, "");
    if (!digits) return 0;
    const value = Number(digits);
    return isNegative ? -value : value;
};

const bindSignedRupiahInput = (el) => {
    el.addEventListener("input", () => {
        const cursorAtEnd =
            el.selectionStart === el.value.length &&
            el.selectionEnd === el.value.length;
        el.value = formatSignedRupiah(el.value);
        if (cursorAtEnd) {
            el.setSelectionRange(el.value.length, el.value.length);
        }
    });
};

const addSalaryFeeRow = (nominal = "", notes = "") => {
    const tpl = document.getElementById("sablon-salary-fee-row-template");
    const row = tpl.content.cloneNode(true);

    const nominalInput = row.querySelector(".sf-nominal");
    nominalInput.value = nominal !== "" ? formatSignedRupiah(nominal) : "";
    row.querySelector(".sf-notes").value = notes;

    document.getElementById("sablon-salary-fee-rows").append(row);

    initLucide();
    bindSignedRupiahInput(nominalInput);
};

const initSalaryFeeModal = () => {
    const modelName = window.langModels?.SalaryEmployee ?? "Salary Employee";

    window.addEventListener("open-sablon-salary-fee-modal", async (e) => {
        const { employeeId, employeeName } = e.detail;
        const weekOf = $("#date_sablon_value").val();

        if (!weekOf) {
            Toast.error(
                window.langCustomAlert.warning,
                window.langSablon?.employee_detail?.pick_date_first ??
                    "Pilih tanggal sablon terlebih dahulu",
            );
            return;
        }

        $("#sablon-salary-fee-employee-id").val(employeeId);
        $("#sablon-salary-fee-week-of").val(weekOf);
        $("#sablon-salary-fee-employee-name").text(employeeName);
        $("#sablon-salary-fee-rows").empty();

        try {
            const res = await fetch(
                route("salary_employees.additional-fee", employeeId) +
                    "?week_of=" +
                    encodeURIComponent(weekOf),
            );
            const data = await res.json();

            $("#sablon-salary-fee-status").val(data.status);

            if (data.additional_fee?.length) {
                data.additional_fee.forEach((af) =>
                    addSalaryFeeRow(af.nominal, af.notes),
                );
            } else {
                addSalaryFeeRow();
            }
        } catch (err) {
            console.error("Failed to load salary additional fee", err);
            addSalaryFeeRow();
        }

        window.HSStaticMethods.autoInit();
        HSOverlay.open("#hs-sablon-salary-fee-modal");
    });

    $(document).on("click", "#btn-add-sablon-salary-fee-row", function () {
        addSalaryFeeRow();
    });

    $(document).on("click", ".btn-remove-sablon-salary-fee-row", function () {
        $(this).closest(".sablon-salary-fee-row").remove();
    });

    $(document).on("click", "#btn-save-sablon-salary-fee", async function () {
        const employeeId = $("#sablon-salary-fee-employee-id").val();
        const weekOf = $("#sablon-salary-fee-week-of").val();
        const status = $("#sablon-salary-fee-status").val();

        const additionalFee = $(
            "#sablon-salary-fee-rows .sablon-salary-fee-row",
        )
            .map(function () {
                const nominalEl = $(this).find(".sf-nominal")[0];
                const nominal = unformatSignedRupiah(nominalEl);
                const notes = $(this).find(".sf-notes").val();
                return { nominal, notes: notes || "" };
            })
            .get()
            .filter((af) => af.nominal !== 0 || af.notes !== "");

        startLoading(this);

        try {
            await ApiProvider.put(
                route("salary_employees.update", employeeId),
                {
                    week_of: weekOf,
                    status,
                    additional_fee: additionalFee,
                },
            );

            Toast.success(
                window.langCustomAlert.success,
                trans("langCrud", "updated", { model: modelName }),
            );

            HSOverlay.close("#hs-sablon-salary-fee-modal");
        } catch (err) {
            console.error(err);
        } finally {
            stopLoading(this);
        }
    });
};

const PageScript = (function () {
    let form, mode, id;
    const modelName = window.langModels?.Sablon ?? "Sablon";

    function bindEvents() {
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;
            const action = submitter?.dataset.action ?? "save";

            if (submitter?.hasAttribute("data-button-loading"))
                startLoading(submitter);
            await submitForm(action, submitter);
        });
    }

    function getAlpineData() {
        const container = form.querySelector("[x-data]");
        return Alpine.$data(container);
    }

    function resetHsSelects(form) {
        form.querySelectorAll("select[data-hs-select]").forEach((select) => {
            const instance = HSSelect.getInstance(select);
            instance?.setValue("");

            const clearBtn = document.querySelector(
                `[data-clear-select="${select.id}"]`,
            );
            if (clearBtn) clearBtn.style.display = "none";
        });
    }

    function resetForm() {
        form.reset();

        const data = getAlpineData();

        const fabricEl = document.getElementById("fabric_id");
        if (fabricEl) {
            const hsInstance = HSSelect.getInstance(fabricEl);
            [...fabricEl.options].forEach((opt) => {
                if (opt.value !== "") hsInstance?.removeOption(opt.value);
            });
        }

        resetHsSelects(form);

        getFlatpickrInstance("date_sablon")?.setDate(new Date(), true);

        data.fabricRows = [];
        data.employeeRows = [];
        data.fabricOptions = [];
        data.selectedFabricId = null;
        data.selectedSupplierId = null;
        data.selectedPriceEmployeeId = null;
        data.selectedTypeColorId = null;
        data.totalSablon = 0;
    }

    function getFabricDetails(data) {
        return data.fabricRows.map((row) => ({
            fabric_detail_id: row.fabric_detail_id,
            color_fabric_id: row.color_fabric_id,
            long_fabric: row.long_fabric,
        }));
    }

    function getEmployeeDetails(data) {
        return data.employeeRows
            .filter((row) => !row.locked)
            .map((row) => {
                const fee = data.computeFee(row);

                return {
                    fabric_detail_id: row.fabric_detail_id || null,
                    employee_id: row.employee_id,
                    layers: row.layers || 0,
                    fee,
                    is_change: row.is_change,
                    employee_change_id: row.is_change
                        ? row.employee_change_id || null
                        : null,
                    is_bon: row.is_bon,
                    is_paid: row.is_paid,
                    notes: row.notes,
                    additional_fees: row.is_bon
                        ? row.additionalFees
                              .filter(
                                  (af) => af.nominal !== 0 || af.notes !== "",
                              )
                              .map((af) => ({
                                  nominal: af.nominal,
                                  notes: af.notes || "",
                              }))
                        : [],
                };
            });
    }

    async function submitForm(action, submitter) {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        const data = getAlpineData();
        payload.fabric_details = getFabricDetails(data);
        payload.employee_details = getEmployeeDetails(data);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("sablons.store"), payload);
                if (action === "save-another") {
                    Toast.success(
                        window.langCustomAlert.success,
                        trans("langCrud", "created", { model: modelName }),
                    );
                    resetForm();
                    return;
                }
                flashToast(
                    "success",
                    window.langCustomAlert.success,
                    trans("langCrud", "created", { model: modelName }),
                );
                window.location.href = route("sablons.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("sablons.update", id), payload);
                flashToast(
                    "success",
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
                window.location.href = route("sablons.index");
            }
        } catch (error) {
            // sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    return {
        init() {
            form = document.getElementById("sablon-form");
            if (!form) return;
            mode = form.dataset.mode;
            id = form.dataset.id;

            bindEvents();
            initSalaryFeeModal();
        },
    };
})();

export default PageScript;
