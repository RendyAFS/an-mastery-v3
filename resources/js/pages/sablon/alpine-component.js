import * as calc from "./calculations";
import reInitUi from "@/utils/reinit-ui";

export default function sablonForm(
    initialFabricRows = [],
    initialEmployeeRows = [],
    fabricDetails = [],
    employees = {},
    priceEmployeesRaw = {},
    typeColorsRaw = {},
    initialFabricId = null,
    initialSupplierId = null,
    initialFabricOptions = {},
) {
    return {
        fabricRows: [],
        employeeRows: [],
        fabricRawData: {},
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
        selectedTypeFabricId: null,
        totalSablon: 0,

        init() {
            this.fabricRows = initialFabricRows.length
                ? initialFabricRows.map((row) => this.buildFabricRow(row))
                : [this.buildFabricRow()];

            this.employeeRows = initialEmployeeRows.map((row) =>
                this.buildEmployeeRow(row),
            );

            this.$nextTick(() => {
                const priceEl = document.getElementById("price_employee_id");
                const colorEl = document.getElementById("type_color_id");
                const totalEl = document.getElementById("total_sablon");

                this.selectedPriceEmployeeId = priceEl?.value || null;
                this.selectedTypeColorId = colorEl?.value || null;
                this.totalSablon = Number(totalEl?.value || 0);

                reInitUi();

                if (this.selectedFabricId) {
                    this.loadFabricDetail(this.selectedFabricId);
                }

                this._bindNativeSelectChange("supplier_id", (val) => {
                    this._handleSupplierChange(val);
                });

                this._bindNativeSelectChange("fabric_id", async (val) => {
                    this.selectedFabricId = val || null;

                    if (val) {
                        await this.loadFabricDetail(val);
                    }

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

        applyRupiahMask() {
            this.$nextTick(() => {
                RupiahInput.init();
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
                    const hsInstance = window.HSSelect.getInstance(fabricEl);
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

                this.fabricRawData = options;

                this.fabricOptions = Object.entries(options).map(
                    ([id, label]) => ({
                        id,
                        label,
                    }),
                );

                this._refreshFabricSelectOptions(options);
            } catch (err) {
                console.error("Failed to load fabrics by supplier", err);
            }
        },

        async loadFabricDetail(fabricId) {
            try {
                const res = await fetch(
                    route("sablons.get-type-fabric", fabricId),
                );

                const fabric = await res.json();

                const typeFabricEl = document.getElementById("type_fabric_id");

                if (typeFabricEl) {
                    typeFabricEl.value = fabric.type_fabric_id ?? "";

                    const hsInstance =
                        window.HSSelect?.getInstance(typeFabricEl);

                    hsInstance?.setValue(String(fabric.type_fabric_id ?? ""));
                }
            } catch (err) {
                console.error("Failed to load fabric detail", err);
            }
        },

        _refreshFabricSelectOptions(options) {
            const fabricEl = document.getElementById("fabric_id");
            if (!fabricEl) return;

            const hsInstance = window.HSSelect?.getInstance(fabricEl);
            if (!hsInstance) return;

            [...fabricEl.options].forEach((opt) => {
                if (opt.value !== "") hsInstance.removeOption(opt.value);
            });

            Object.entries(options).forEach(([id, label]) => {
                hsInstance.addOption({
                    title: label,
                    val: id,
                });
            });
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
                (d) => String(d.fabric_id) === String(this.selectedFabricId),
            );

            if (matchingDetails.length > 0) {
                this.fabricRows = matchingDetails.map((d) =>
                    this.buildFabricRow({
                        fabric_detail_id: d.id,
                        color_fabric_id: d.color_fabric_id,
                        long_fabric: 0,
                    }),
                );
            } else {
                this.fabricRows = [this.buildFabricRow()];
            }

            this.$nextTick(() => reInitUi());
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
                long_fabric: row.long_fabric ?? 0,
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
                layers: row.layers ?? 1,
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
                searchEmp: "",
                searchEmpChange: "",
            };
        },

        addFabricRow() {
            this.fabricRows.push(this.buildFabricRow());
            this.$nextTick(() => reInitUi());
        },

        removeFabricRow(index) {
            if (this.fabricRows.length <= 1) return;
            this.fabricRows.splice(index, 1);
        },

        addEmployeeRow() {
            this.employeeRows.push(this.buildEmployeeRow());
            this.$nextTick(() => reInitUi());
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
            this.$nextTick(() => reInitUi());
        },

        removeAdditionalFee(row, feeIndex) {
            if (Array.isArray(row.additional_fee)) {
                row.additional_fee.splice(feeIndex, 1);
            }
        },

        filteredEmployees(search) {
            if (!search) return this.employeeOptions;
            const q = search.toLowerCase();
            return this.employeeOptions.filter((e) =>
                e.name.toLowerCase().includes(q),
            );
        },

        filteredFabricDetails(search) {
            let options = this.fabricDetailOptions.filter(
                (d) => String(d.fabric_id) === String(this.selectedFabricId),
            );
            if (search) {
                options = options.filter((d) =>
                    d.color_name.toLowerCase().includes(search.toLowerCase()),
                );
            }
            return options;
        },

        fabricDetailLabel(id) {
            const found = this.fabricDetailOptions.find(
                (d) => String(d.id) === String(id),
            );
            return found ? `${found.color_name} (stock: ${found.stock})` : "";
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
            return calc.formatNumber(val);
        },

        formatSignedNumber(val) {
            return calc.formatSignedNumber(val);
        },

        parseSignedNumber(value) {
            return calc.parseSignedNumber(value);
        },

        get totalLongFabric() {
            return calc.totalLongFabric(this.fabricRows);
        },

        get computedTotalSablon() {
            return calc.computeTotalSablon(
                this.totalLongFabric,
                this.priceEmployeesRaw[this.selectedPriceEmployeeId],
            );
        },

        get ratePerLayer() {
            return calc.computeRatePerLayer(
                this.computedTotalSablon,
                this.typeColorsRaw[this.selectedTypeColorId],
            );
        },

        computeFee(row) {
            const fee = calc.computeFee(this.ratePerLayer, row.layers);
            row.fee = fee;
            return fee;
        },

        additionalFeeTotal(row) {
            return calc.additionalFeeTotal(row.additional_fee);
        },

        rowTotal(row) {
            const fee = this.computeFee(row);
            const total = calc.computeRowTotal(fee, row.additional_fee);
            row.total = total;
            return total;
        },
    };
}
