import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

document.addEventListener("alpine:init", () => {
    Alpine.data(
        "sablonForm",
        (
            initialFabricRows = [],
            initialEmployeeRows = [],
            fabricDetails = [],
            employees = {},
            priceEmployeesRaw = {},
            typeColorsRaw = {},
            initialFabricId = null,
            initialSupplierId = null,
            initialFabricOptions = {},
        ) => ({
            fabricRows: [],
            employeeRows: [],
            fabricDetailOptions: fabricDetails,
            fabricOptions: Object.entries(initialFabricOptions).map(
                ([id, label]) => ({ id, label }),
            ),
            employeeOptions: Object.entries(employees).map(([id, name]) => ({
                id,
                name,
            })),
            priceEmployeesRaw,
            typeColorsRaw,
            selectedFabricId: initialFabricId ? String(initialFabricId) : null,
            selectedSupplierId: initialSupplierId
                ? String(initialSupplierId)
                : null,
            selectedPriceEmployeeId: null,
            selectedTypeColorId: null,
            totalSablon: 0,

            init() {
                this.fabricRows = initialFabricRows.length
                    ? initialFabricRows.map((row) => this.buildFabricRow(row))
                    : [this.buildFabricRow()];

                this.employeeRows = initialEmployeeRows.map((row) =>
                    this.buildEmployeeRow(row),
                );

                this.$nextTick(() => {
                    const priceEl =
                        document.getElementById("price_employee_id");
                    const colorEl = document.getElementById("type_color_id");
                    const totalEl = document.getElementById("total_sablon");

                    this.selectedPriceEmployeeId = priceEl?.value || null;
                    this.selectedTypeColorId = colorEl?.value || null;
                    this.totalSablon = Number(totalEl?.value || 0);

                    window.lucide?.createIcons();

                    this._bindNativeSelectChange("supplier_id", (val) => {
                        this._handleSupplierChange(val);
                    });

                    this._bindNativeSelectChange("fabric_id", (val) => {
                        this.selectedFabricId = val || null;
                        this._autoPopulateFabricRows();
                    });

                    this._bindNativeSelectChange("price_employee_id", (val) => {
                        this.selectedPriceEmployeeId = val || null;
                    });

                    this._bindNativeSelectChange("type_color_id", (val) => {
                        this.selectedTypeColorId = val || null;
                    });
                });
            },

            _bindNativeSelectChange(id, callback) {
                const el = document.getElementById(id);
                if (!el) return;
                el.addEventListener("change", (e) => {
                    callback(e.target.value || null);
                });
            },

            async _handleSupplierChange(supplierId) {
                this.selectedSupplierId = supplierId || null;
                this.fabricOptions = [];
                this.selectedFabricId = null;
                this.fabricRows = [this.buildFabricRow()];

                const fabricEl = document.getElementById("fabric_id");
                if (fabricEl) {
                    fabricEl.value = "";
                    if (window.HSSelect) {
                        const hsInstance =
                            window.HSSelect.getInstance(fabricEl);
                        hsInstance?.setValue("");
                    }
                }

                if (!this.selectedSupplierId) return;

                try {
                    const res = await fetch(
                        route(
                            "sablons.fabrics-by-supplier",
                            this.selectedSupplierId,
                        ),
                    );
                    const options = await res.json();

                    this.fabricOptions = Object.entries(options).map(
                        ([id, label]) => ({ id, label }),
                    );

                    this._refreshFabricSelectOptions(options);
                } catch (err) {
                    console.error("Failed to load fabrics by supplier", err);
                }
            },

            _refreshFabricSelectOptions(options) {
                const fabricEl = document.getElementById("fabric_id");
                if (!fabricEl) return;

                if (window.HSSelect) {
                    const hsInstance = window.HSSelect.getInstance(fabricEl);
                    hsInstance?.destroy();
                }

                fabricEl.innerHTML = '<option value=""></option>';
                Object.entries(options).forEach(([id, label]) => {
                    const opt = document.createElement("option");
                    opt.value = id;
                    opt.textContent = label;
                    fabricEl.appendChild(opt);
                });

                if (window.HSSelect) {
                    new window.HSSelect(fabricEl);
                }
            },
            onSupplierChange(e) {
                this._handleSupplierChange(e.target.value || null);
            },

            onFabricChange(e) {
                this.selectedFabricId = e.target.value || null;
                this._autoPopulateFabricRows();
            },

            onPriceEmployeeChange(e) {
                this.selectedPriceEmployeeId = e.target.value || null;
            },

            onTypeColorChange(e) {
                this.selectedTypeColorId = e.target.value || null;
            },

            _autoPopulateFabricRows() {
                if (!this.selectedFabricId) {
                    this.fabricRows = [this.buildFabricRow()];
                    return;
                }

                const matchingDetails = this.fabricDetailOptions.filter(
                    (d) =>
                        String(d.fabric_id) === String(this.selectedFabricId),
                );

                if (matchingDetails.length > 0) {
                    this.fabricRows = matchingDetails.map((d) =>
                        this.buildFabricRow({
                            fabric_detail_id: d.id,
                            color_fabric_id: d.color_fabric_id,
                            long_fabric: "",
                        }),
                    );
                } else {
                    this.fabricRows = [this.buildFabricRow()];
                }

                this.$nextTick(() => window.lucide?.createIcons());
            },

            buildFabricRow(row = {}) {
                return {
                    uid: crypto.randomUUID(),
                    fabric_detail_id: row.fabric_detail_id
                        ? String(row.fabric_detail_id)
                        : "",
                    color_fabric_id: row.color_fabric_id
                        ? String(row.color_fabric_id)
                        : "",
                    long_fabric: row.long_fabric ?? "",
                    open: false,
                    search: "",
                };
            },

            buildEmployeeRow(row = {}) {
                let additionalFee = [];
                if (Array.isArray(row.additional_fee)) {
                    additionalFee = row.additional_fee.map((f) => ({
                        uid: crypto.randomUUID(),
                        nominal: f.nominal ?? 0,
                        notes: f.notes ?? "",
                    }));
                } else if (
                    row.additional_fee &&
                    typeof row.additional_fee === "object"
                ) {
                    additionalFee = [
                        {
                            uid: crypto.randomUUID(),
                            nominal: row.additional_fee.nominal ?? 0,
                            notes: row.additional_fee.notes ?? "",
                        },
                    ];
                }

                return {
                    uid: crypto.randomUUID(),
                    fabric_detail_id: row.fabric_detail_id
                        ? String(row.fabric_detail_id)
                        : "",
                    employee_id: row.employee_id ? String(row.employee_id) : "",
                    layers: row.layers ?? "",
                    fee: row.fee ?? 0,
                    additional_fee: additionalFee,
                    total: row.total ?? 0,
                    is_change: !!row.is_change,
                    employee_change_id: row.employee_change_id
                        ? String(row.employee_change_id)
                        : "",
                    is_payed: !!row.is_payed,
                    notes: row.notes ?? "",
                    openEmp: false,
                    openEmpChange: false,
                };
            },

            addFabricRow() {
                this.fabricRows.push(this.buildFabricRow());
                this.$nextTick(() => window.lucide?.createIcons());
            },
            removeFabricRow(index) {
                if (this.fabricRows.length <= 1) return;
                this.fabricRows.splice(index, 1);
            },
            addEmployeeRow() {
                this.employeeRows.push(this.buildEmployeeRow());
                this.$nextTick(() => window.lucide?.createIcons());
            },
            removeEmployeeRow(index) {
                this.employeeRows.splice(index, 1);
            },

            addAdditionalFee(row) {
                if (!Array.isArray(row.additional_fee)) {
                    row.additional_fee = [];
                }
                row.additional_fee.push({
                    uid: crypto.randomUUID(),
                    nominal: 0,
                    notes: "",
                });
                this.$nextTick(() => window.lucide?.createIcons());
            },

            removeAdditionalFee(row, feeIndex) {
                if (Array.isArray(row.additional_fee)) {
                    row.additional_fee.splice(feeIndex, 1);
                }
            },

            filteredFabricDetails(search) {
                let options = this.fabricDetailOptions.filter(
                    (d) =>
                        String(d.fabric_id) === String(this.selectedFabricId),
                );
                if (search) {
                    options = options.filter((d) =>
                        d.color_name
                            .toLowerCase()
                            .includes(search.toLowerCase()),
                    );
                }
                return options;
            },

            fabricDetailLabel(id) {
                const found = this.fabricDetailOptions.find(
                    (d) => String(d.id) === String(id),
                );
                return found
                    ? `${found.color_name} (stock: ${found.stock})`
                    : "";
            },

            employeeLabel(id) {
                const found = this.employeeOptions.find(
                    (e) => String(e.id) === String(id),
                );
                return found ? found.name : "";
            },

            selectFabricDetail(row, option) {
                row.fabric_detail_id = option.id;
                row.color_fabric_id = option.color_fabric_id;
                row.open = false;
                row.search = "";
            },

            formatNumber(val) {
                return new Intl.NumberFormat("id-ID").format(Number(val) || 0);
            },
            get computedTotalSablon() {
                const totalLong = this.totalLongFabric;
                const price = Number(
                    this.priceEmployeesRaw[this.selectedPriceEmployeeId] || 0,
                );
                const colorCount = Number(
                    this.typeColorsRaw[this.selectedTypeColorId] || 0,
                );
                if (!colorCount || !price) return 0;
                return (totalLong * price) / colorCount;
            },

            get ratePerLayer() {
                return this.computedTotalSablon;
            },

            computeFee(row) {
                const fee = this.ratePerLayer * (Number(row.layers) || 0);
                row.fee = fee;
                return fee;
            },

            additionalFeeTotal(row) {
                if (!Array.isArray(row.additional_fee)) return 0;
                return row.additional_fee.reduce(
                    (sum, f) => sum + (Number(f.nominal) || 0),
                    0,
                );
            },

            rowTotal(row) {
                const fee = this.computeFee(row);
                const additional = this.additionalFeeTotal(row);
                const total = fee + additional;
                row.total = total;
                return total;
            },

            get totalLongFabric() {
                return this.fabricRows.reduce(
                    (sum, row) => sum + (Number(row.long_fabric) || 0),
                    0,
                );
            },
        }),
    );
});

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

$(function () {
    PageScript.init();
});
