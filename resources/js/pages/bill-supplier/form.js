import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    let form, mode, id, supplierId;

    const loadSablonOptions = async () => {
        const select = document.getElementById("sablon_id");
        if (!select) return;

        try {
            const response = await ApiProvider.get(
                route("bill_suppliers.available-sablons", supplierId),
            );
            const sablons = response.data ?? [];

            select.innerHTML =
                '<option value="">Pilih Sablon...</option>' +
                sablons
                    .map((s) => `<option value="${s.id}">${s.label}</option>`)
                    .join("");
        } catch (error) {
            console.error("Load available sablons error:", error);
        }
    };

    const handleSablonChange = async () => {
        const sablonId = $("#sablon_id").val();
        const preview = $("#bs-calc-preview");

        if (!sablonId) {
            preview.addClass("hidden");
            return;
        }

        try {
            const calc = await ApiProvider.get(
                route("bill_suppliers.calculate", sablonId),
            );

            $("#calc-total-long-fabric").text(
                `${calc.total_long_fabric ?? 0} m`,
            );
            $("#calc-price").text(`Rp${RupiahInput.format(calc.price ?? 0)}`);
            $("#calc-total-fee").text(
                `Rp${RupiahInput.format(calc.total_fee ?? 0)}`,
            );

            preview.removeClass("hidden");

            if (!calc.price_supplier_id) {
                Toast.error(
                    "Perhatian",
                    "Harga supplier untuk kombinasi fabric & warna ini belum tersedia",
                );
            }
        } catch (error) {
            console.error("Calculate error:", error);
            preview.addClass("hidden");
        }
    };

    const submitForm = async (submitter) => {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("bill_suppliers.store"), payload);
                Toast.success("Success", "Bill Supplier Successfully Created");
            }

            if (mode === "edit") {
                await ApiProvider.put(
                    route("bill_suppliers.update", id),
                    payload,
                );
                Toast.success("Success", "Bill Supplier Successfully Updated");
            }

            window.location.href = route(
                "bill_suppliers.by-supplier",
                supplierId,
            );
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const bindEvents = () => {
        $(document).on("change", "#sablon_id", handleSablonChange);

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;

            if (submitter?.hasAttribute("data-button-loading")) {
                startLoading(submitter);
            }

            await submitForm(submitter);
        });
    };

    return {
        init() {
            form = document.getElementById("bill-supplier-form");
            if (!form) return;

            mode = form.dataset.mode;
            id = form.dataset.id;
            supplierId = form.dataset.supplierId;

            bindEvents();

            if (mode === "create") {
                loadSablonOptions();
            }
        },
    };
})();

$(function () {
    PageScript.init();
});
