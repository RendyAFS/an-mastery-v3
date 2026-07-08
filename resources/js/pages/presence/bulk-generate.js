import ApiProvider from "@/utils/api-provider";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";

const rawNumber = (value) => {
    const num = parseInt(String(value ?? "").replace(/\D/g, ""), 10) || 0;
    return Math.max(num, 0);
};

const isoWeekToMonday = (isoWeekStr) => {
    const [yearStr, weekStr] = isoWeekStr.split("-W");
    const year = parseInt(yearStr, 10);
    const week = parseInt(weekStr, 10);

    const jan4 = new Date(year, 0, 4);
    const jan4Day = jan4.getDay() || 7;
    const week1Monday = new Date(jan4);
    week1Monday.setDate(jan4.getDate() - jan4Day + 1);

    const target = new Date(week1Monday);
    target.setDate(week1Monday.getDate() + (week - 1) * 7);
    return target;
};

const dateToIsoWeek = (date) => {
    const d = new Date(date);
    d.setHours(0, 0, 0, 0);
    d.setDate(d.getDate() + 3 - ((d.getDay() + 6) % 7));
    const week1 = new Date(d.getFullYear(), 0, 4);
    const weekNo =
        1 +
        Math.round(
            ((d - week1) / 86400000 - 3 + ((week1.getDay() + 6) % 7)) / 7,
        );
    return `${d.getFullYear()}-W${String(weekNo).padStart(2, "0")}`;
};

const toDateStr = (date) => {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
};

/**
 * Self-contained controller for the "Generate Presence" bulk modal.
 * Depends on: #btn-bulk-generate, #hs-bulk-generate-modal, #bulk-generate-form,
 * #bulk_week_of, #bulk_amount, #bulk_check_all, #bulk_employee_list.
 */
const BulkGenerateModal = (function () {
    let getCurrentWeekOf = () => new Date();
    let onGenerated = () => {};

    const loadEmployees = async () => {
        try {
            const response = await ApiProvider.get(
                route("presences.employees"),
            );
            return response.data ?? [];
        } catch (error) {
            console.error("Fetch employees error:", error);
            return [];
        }
    };

    const renderEmployeeList = (employees) => {
        const $list = $("#bulk_employee_list");
        $list.empty();

        if (employees.length === 0) {
            $list.append(
                `<p class="px-3 py-2 text-sm text-(--color-gray)">No employees found</p>`,
            );
            return;
        }

        employees.forEach((employee) => {
            $list.append(`
                <label class="flex items-center gap-2 px-3 py-2 text-sm cursor-pointer">
                    <input type="checkbox" class="bulk-employee-checkbox checkbox-custom" value="${employee.id}" />
                    ${employee.name}
                </label>
            `);
        });
    };

    const resetForm = async () => {
        $("#bulk_week_of").val(dateToIsoWeek(getCurrentWeekOf()));
        $("#bulk_amount").val("10000");
        RupiahInput.refresh(document.getElementById("bulk_amount"));
        $("#bulk_check_all").prop("checked", false);

        renderEmployeeList([]);
        const employees = await loadEmployees();
        renderEmployeeList(employees);
    };

    const open = async () => {
        await resetForm();
        HSOverlay.open("#hs-bulk-generate-modal");
    };

    const close = () => HSOverlay.close("#hs-bulk-generate-modal");

    const submit = async (submitter) => {
        const weekValue = $("#bulk_week_of").val();
        const amount = rawNumber($("#bulk_amount").val());
        const employeeIds = $(".bulk-employee-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

        if (!weekValue) {
            Toast.error("Error", "Please select a week");
            stopLoading(submitter);
            return;
        }

        if (employeeIds.length === 0) {
            Toast.error("Error", "Please select at least one employee");
            stopLoading(submitter);
            return;
        }

        const payload = {
            week_of: toDateStr(isoWeekToMonday(weekValue)),
            amount,
            employee_ids: employeeIds,
        };

        try {
            const response = await ApiProvider.post(
                route("presences.bulkGenerate"),
                payload,
            );
            Toast.success(
                "Success",
                response.message ?? "Presence generated successfully",
            );
            close();
            onGenerated();
        } catch (error) {
            console.error("Bulk generate error:", error);
        } finally {
            stopLoading(submitter);
        }
    };

    const bindEvents = () => {
        $("#btn-bulk-generate").on("click", open);

        $(document).on("change", "#bulk_check_all", function () {
            $(".bulk-employee-checkbox").prop(
                "checked",
                $(this).is(":checked"),
            );
        });

        $(document).on("change", ".bulk-employee-checkbox", function () {
            const total = $(".bulk-employee-checkbox").length;
            const checked = $(".bulk-employee-checkbox:checked").length;
            $("#bulk_check_all").prop(
                "checked",
                total > 0 && total === checked,
            );
        });

        document
            .getElementById("bulk-generate-form")
            .addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitter = e.submitter;
                if (submitter?.hasAttribute("data-button-loading"))
                    startLoading(submitter);
                await submit(submitter);
            });

        document
            .getElementById("hs-bulk-generate-modal")
            .addEventListener("close.hs.overlay", () => {
                document.getElementById("bulk-generate-form").reset();
            });
    };

    return {
        /**
         * @param {Object} options
         * @param {() => Date} options.currentWeekOf - returns the Monday currently active on the page
         * @param {() => void} options.onGenerated - called after a successful bulk generate (e.g. reload datatable)
         */
        init({ currentWeekOf, onGenerated: onGeneratedCb } = {}) {
            if (typeof currentWeekOf === "function")
                getCurrentWeekOf = currentWeekOf;
            if (typeof onGeneratedCb === "function")
                onGenerated = onGeneratedCb;
            bindEvents();
        },
    };
})();

export default BulkGenerateModal;
