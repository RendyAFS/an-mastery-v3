import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import trans from "@/utils/trans";

document.addEventListener("alpine:init", () => {
    Alpine.data("fabricForm", (initialRows = [], colorFabrics = {}) => ({
        rows: [],
        colorOptions: colorFabrics.map((item) => ({
            id: item.id,
            name: item.name,
        })),

        init() {
            this.rows = initialRows.length
                ? initialRows.map((row) => this.buildRow(row))
                : Array.from({ length: 4 }, () => this.buildRow());

            this.$nextTick(() => window.lucide?.createIcons());
        },

        buildRow(row = {}) {
            return {
                uid: crypto.randomUUID(),
                id: row.id ?? null,
                color_fabric_id: row.color_fabric_id
                    ? String(row.color_fabric_id)
                    : "",
                stock: row.stock ?? "",
                notes: row.notes ?? "",
                open: false,
                search: "",
            };
        },

        addRow() {
            this.rows.push(this.buildRow());
            this.$nextTick(() => window.lucide?.createIcons());
        },

        removeRow(index) {
            this.rows.splice(index, 1);
            this.$nextTick(() => window.lucide?.createIcons());
        },

        isColorSelectedInOtherRow(colorId, currentRowUid) {
            if (!colorId) return false;
            return this.rows.some(
                (r) =>
                    r.uid !== currentRowUid &&
                    String(r.color_fabric_id) === String(colorId),
            );
        },

        selectColor(row, colorId) {
            if (this.isColorSelectedInOtherRow(colorId, row.uid)) {
                return;
            }
            row.color_fabric_id = String(colorId);
            row.open = false;
            row.search = "";
        },

        colorName(id) {
            return (
                this.colorOptions.find((c) => String(c.id) === String(id))
                    ?.name ?? ""
            );
        },

        filteredColors(search) {
            if (!search) return this.colorOptions;

            return this.colorOptions.filter((c) =>
                c.name.toLowerCase().includes(search.toLowerCase()),
            );
        },

        get totalStock() {
            return this.rows.reduce(
                (sum, row) => sum + (Number(row.stock) || 0),
                0,
            );
        },
    }));
});

const PageScript = (function () {
    let form, mode, id;
    const modelName = window.langModels?.Fabric ?? "Fabric";

    function bindEvents() {
        if (!form) return;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const submitter = e.submitter;
            const action = submitter?.dataset.action ?? "save";

            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }

            await submitForm(action, submitter);
        });
    }

    function getAlpineData() {
        const container = form.querySelector("[x-data]");
        return Alpine.$data(container);
    }

    function resetForm() {
        form.reset();

        form.querySelectorAll("select[data-hs-select]").forEach((select) => {
            const instance = HSSelect.getInstance(select);
            instance?.setValue("");

            const clearBtn = document.querySelector(
                `[data-clear-select="${select.id}"]`,
            );
            if (clearBtn) clearBtn.style.display = "none";
        });

        const data = getAlpineData();
        data.rows = Array.from({ length: 4 }, () => data.buildRow());
    }

    function getFabricDetails() {
        const data = getAlpineData();

        return data.rows.map((row) => ({
            id: row.id ?? null,
            color_fabric_id: row.color_fabric_id,
            stock: row.stock,
            notes: row.notes,
        }));
    }

    async function submitForm(action, submitter) {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(form, payload);
        const details = getFabricDetails();

        const colorIds = details
            .map((d) => d.color_fabric_id)
            .filter((id) => id !== "" && id != null);
        const hasDuplicate = colorIds.some(
            (val, i) => colorIds.indexOf(val) !== i,
        );

        if (hasDuplicate) {
            Toast.error(
                window.langCustomAlert?.error ?? "Error",
                trans(
                    "langFabric",
                    "validation.fabric_details.color_fabric_id.distinct",
                ),
            );
            return;
        }

        payload.fabric_details = details;

        try {
            if (mode === "create") {
                await ApiProvider.post(route("fabrics.store"), payload);

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
                window.location.href = route("fabrics.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("fabrics.update", id), payload);
                flashToast(
                    "success",
                    window.langCustomAlert.success,
                    trans("langCrud", "updated", { model: modelName }),
                );
                window.location.href = route("fabrics.index");
            }
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    }

    return {
        init() {
            form = document.getElementById("fabric-form");
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
