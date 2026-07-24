import ApiProvider from "@/utils/api-provider";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";

const PageScript = (function () {
    let form, mode, id, supplierId;

    const loadSablonList = async () => {
        const listContainer = $("#sablon-modal-list");
        const loadingEl = $("#sablon-modal-loading");
        const emptyEl = $("#sablon-modal-empty");

        loadingEl.removeClass("hidden");
        listContainer.addClass("hidden").empty();
        emptyEl.addClass("hidden");

        try {
            const response = await ApiProvider.get(
                route("bill_suppliers.available-sablons", supplierId),
            );
            const sablons = response.data ?? [];

            if (!sablons.length) {
                emptyEl.removeClass("hidden");
                return;
            }

            listContainer.html(
                sablons
                    .map(
                        (s) => `
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-(--color-gray)/10 cursor-pointer">
                            <input type="checkbox" class="checkbox-custom sablon-checkbox" value="${s.id}" checked>
                            <span class="text-sm">${s.label}</span>
                        </label>
                    `,
                    )
                    .join(""),
            );

            listContainer.removeClass("hidden");

            $("#sablon-check-all").prop("checked", true).prop("indeterminate", false);

            syncSelection();
        } catch (error) {
            console.error("Load available sablons error:", error);
        } finally {
            loadingEl.addClass("hidden");
        }
    };

    const getCheckedIds = () =>
        $(".sablon-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

    const syncCheckAllState = () => {
        const checkAll = document.getElementById("sablon-check-all");
        if (!checkAll) return;

        const total = $(".sablon-checkbox").length;
        const checked = $(".sablon-checkbox:checked").length;

        checkAll.checked = total > 0 && checked === total;
        checkAll.indeterminate = checked > 0 && checked < total;
    };

    const syncSelection = () => {
        const ids = getCheckedIds();

        const summaryEl = $("#sablon-selected-summary");
        summaryEl.text(ids.length ? `${ids.length} sablon dipilih` : "Belum ada sablon dipilih");

        syncCheckAllState();
        loadBulkPreview(ids);
    };

    const loadBulkPreview = async (ids) => {
        const preview = $("#bs-calc-preview");

        if (!ids.length) {
            preview.addClass("hidden");
            return;
        }

        try {
            const calc = await ApiProvider.post(route("bill_suppliers.calculate-bulk"), {
                sablon_ids: ids,
            });

            $("#calc-count").text(`${ids.length} sablon`);
            $("#calc-total-long-fabric").text(`${calc.total_long_fabric ?? 0} m`);
            $("#calc-total-fee").text(`Rp${RupiahInput.format(calc.total_fee ?? 0)}`);

            const missingPrice = (calc.data ?? []).some((item) => !item.price_supplier_id);

            if (missingPrice) {
                Toast.error(
                    "Perhatian",
                    "Beberapa sablon belum memiliki harga supplier untuk kombinasi fabric & warna",
                );
            }

            preview.removeClass("hidden");
        } catch (error) {
            console.error("Calculate bulk error:", error);
            preview.addClass("hidden");
        }
    };

    const submitForm = async (submitter) => {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());

        if (mode === "create") {
            const sablonIds = getCheckedIds();

            if (!sablonIds.length) {
                Toast.error("Perhatian", "Pilih minimal 1 sablon terlebih dahulu");
                stopLoading(submitter);
                return;
            }

            payload.sablon_ids = sablonIds;
        }

        payload = normalizeFormInputs(form, payload);

        try {
            if (mode === "create") {
                await ApiProvider.post(route("bill_suppliers.store"), payload);
                Toast.success("Success", "Bill Supplier Successfully Created");
            }

            if (mode === "edit") {
                await ApiProvider.put(route("bill_suppliers.update", id), payload);
                Toast.success("Success", "Bill Supplier Successfully Updated");
            }

            window.location.href = route("bill_suppliers.by-supplier", supplierId);
        } catch (error) {
            // error sudah ditangani ApiProvider
        } finally {
            stopLoading(submitter);
        }
    };

    const bindEvents = () => {
        $(document).on("change", "#sablon-check-all", function () {
            $(".sablon-checkbox").prop("checked", this.checked);
        });

        $(document).on("change", ".sablon-checkbox", function () {
            syncCheckAllState();
        });

        $(document).on("click", "#btn-confirm-sablon", function () {
            syncSelection();
            HSOverlay.close("#hs-select-sablon-modal");
        });

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
                loadSablonList();
            }
        },
    };
})();

$(function () {
    PageScript.init();
});
