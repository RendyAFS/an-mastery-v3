import Alpine from "alpinejs";
import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

document.addEventListener("alpine:init", () => {
    Alpine.data("fabricForm", (initialRows = [], colorFabrics = {}) => ({
        rows: [],
        colorOptions: Object.entries(colorFabrics).map(([id, name]) => ({
            id,
            name,
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

        const data = getAlpineData();
        data.rows = Array.from({ length: 4 }, () => data.buildRow());
    }

    function getFabricDetails() {
        const data = getAlpineData();

        return data.rows.map((row) => ({
            color_fabric_id: row.color_fabric_id,
            stock: row.stock,
            notes: row.notes,
        }));
    }

    async function submitForm(action, submitter) {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        payload = normalizeFormInputs(form, payload);
        payload.fabric_details = getFabricDetails();

        try {
            if (mode === "create") {
                await ApiProvider.post(route("fabrics.store"), payload);

                if (action === "save-another") {
                    Toast.success("Success", "Fabric Successfully Created");
                    resetForm();
                    return;
                }

                flashToast("success", "Success", "Fabric Successfully Created");
                window.location.href = route("fabrics.index");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("fabrics.update", id), payload);
                flashToast("success", "Success", "Fabric Successfully Updated");
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
