import ApiProvider from "@/utils/api-provider";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { mondayOfWeek, toDateStr } from "@/utils/week";

const rawNumber = (value) => {
    const num = parseInt(String(value ?? "").replace(/\D/g, ""), 10) || 0;
    return Math.max(num, 0);
};

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
                `<p class="px-3 py-2 text-sm text-(--color-gray)">${window.langPresence.bulk.no_employees_found}</p>`,
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
        const instance = getFlatpickrInstance("bulk_week_of");
        instance?.setDate(getCurrentWeekOf(), true);

        $("#bulk_amount").val("0");
        RupiahInput.refresh(document.getElementById("bulk_amount"));
        $("#bulk_check_all").prop("checked", false);
        $("#bulk_check_all_days").prop("checked", true);
        $(".bulk-day-checkbox").each(function () {
            $(this).prop("checked", $(this).data("day") !== "sunday");
        });

        renderEmployeeList([]);
        const employees = await loadEmployees();
        renderEmployeeList(employees);
    };

    const open = async () => {
        await resetForm();
        HSOverlay.open("#hs-bulk-generate-modal");
    };

    const close = () => HSOverlay.close("#hs-bulk-generate-modal");

    const handleQuickAmount = (btn) => {
        const target = document.querySelector(btn.dataset.target);
        if (!target) return;

        target.value = btn.dataset.quickAmount;
        target.dispatchEvent(new Event("input", { bubbles: true }));
    };

    const submit = async (submitter) => {
        const weekValue = $("#bulk_week_of_start").val();
        const amount = rawNumber($("#bulk_amount").val());
        const days = $(".bulk-day-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();
        const employeeIds = $(".bulk-employee-checkbox:checked")
            .map(function () {
                return $(this).val();
            })
            .get();

        if (!weekValue) {
            Toast.error(
                window.langCustomAlert.error,
                window.langPresence.bulk.select_week_error,
            );
            stopLoading(submitter);
            return;
        }

        if (days.length === 0) {
            Toast.error(
                window.langCustomAlert.error,
                window.langPresence.bulk.select_day_error,
            );
            stopLoading(submitter);
            return;
        }

        if (employeeIds.length === 0) {
            Toast.error(
                window.langCustomAlert.error,
                window.langPresence.bulk.select_employee_error,
            );
            stopLoading(submitter);
            return;
        }

        const payload = {
            week_of: weekValue
                ? toDateStr(mondayOfWeek(new Date(weekValue)))
                : "",
            amount,
            days,
            employee_ids: employeeIds,
        };

        try {
            const response = await ApiProvider.post(
                route("presences.bulkGenerate"),
                payload,
            );
            Toast.success(
                window.langCustomAlert.success,
                response.message ?? window.langPresence.bulk.generated_success,
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

        $(document).on("click", "[data-quick-amount]", function () {
            handleQuickAmount(this);
        });

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

        $(document).on("change", "#bulk_check_all_days", function () {
            const isChecked = $(this).is(":checked");
            $(".bulk-day-checkbox").each(function () {
                if ($(this).data("day") !== "sunday") {
                    $(this).prop("checked", isChecked);
                }
            });
        });

        $(document).on("change", ".bulk-day-checkbox", function () {
            const weekdays = $(".bulk-day-checkbox").filter(function () {
                return $(this).data("day") !== "sunday";
            });
            const checkedWeekdays = weekdays.filter(":checked");
            $("#bulk_check_all_days").prop(
                "checked",
                weekdays.length > 0 &&
                    weekdays.length === checkedWeekdays.length,
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
         * @param {() => Date} options.currentWeekOf
         * @param {() => void} options.onGenerated
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
