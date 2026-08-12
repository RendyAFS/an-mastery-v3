import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";

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
        return data.employeeRows.map((row) => {
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
                          .filter((af) => af.nominal !== 0 || af.notes !== "")
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
        },
    };
})();

export default PageScript;
