import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const PageScript = (function () {
    let form, mode, id;

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

    function resetForm() {
        form.reset();
        const data = getAlpineData();
        data.fabricRows = [data.buildFabricRow()];
        data.employeeRows = [];
        data.selectedFabricId = null;
        data.selectedSupplierId = null;
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
            data.rowTotal(row);

            const additionalFees = Array.isArray(row.additional_fee)
                ? row.additional_fee.map((f) => ({
                      nominal: Number(f.nominal) || 0,
                      notes: f.notes || "",
                  }))
                : [];

            return {
                fabric_detail_id: row.fabric_detail_id || null,
                employee_id: row.employee_id,
                layers: row.layers || 0,
                fee: row.fee || 0,
                additional_fee: additionalFees,
                total: row.total || 0,
                is_change: row.is_change,
                employee_change_id: row.is_change
                    ? row.employee_change_id || null
                    : null,
                is_payed: row.is_payed,
                notes: row.notes,
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
                    Toast.success("Success", "Sablon Successfully Created");
                    resetForm();
                    return;
                }
                flashToast("success", "Success", "Sablon Successfully Created");
                window.location.href = route("sablons.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("sablons.update", id), payload);
                flashToast("success", "Success", "Sablon Successfully Updated");
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
