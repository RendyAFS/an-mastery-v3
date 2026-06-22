import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";

const DAYS = [
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday",
    "sunday",
];

const PageScript = (function () {
    let datatable;
    let form;
    let currentWeekOf;
    let currentEmployeeId;

    const formatRupiah = (value) =>
        "Rp" + Number(value || 0).toLocaleString("id-ID");

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

    const toDateStr = (date) => date.toISOString().split("T")[0];

    const formatThDate = (date) =>
        date.toLocaleDateString("id-ID", { day: "2-digit", month: "2-digit" });

    const updateTheadDates = (mondayDate) => {
        DAYS.forEach((day, index) => {
            const date = new Date(mondayDate);
            date.setDate(mondayDate.getDate() + index);
            $(`#th-${day} .th-date`).text(formatThDate(date));
        });
    };

    const setCurrentWeek = (mondayDate) => {
        currentWeekOf = toDateStr(mondayDate);
        updateTheadDates(mondayDate);
    };

    const reloadDatatable = () => {
        datatable.ajax.reload(null, false);
    };

    const initDataTable = () => {
        datatable = initDatatable({
            table: "#presences-datatable",
            filterSelector: "#filter-presences",
            onRowClick: (row) => handleRowClick(row.employee_id),
            ajax: {
                url: route("presences.data"),
                method: "GET",
                dataSrc: "data",
                data: function (d) {
                    d.filter = $("#filter-presences").val();
                    d.week_of = currentWeekOf;
                },
            },
            columns: [
                {
                    data: "employee_name",
                    render: (name, type, row) =>
                        row.is_deleted
                            ? `<span class="text-(--color-red) line-through">${name}</span>`
                            : name,
                },
                {
                    data: "monday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "tuesday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "wednesday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "thursday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "friday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "saturday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "sunday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    data: "total",
                    className: "text-center font-semibold",
                    render: formatRupiah,
                },
                { data: "notes" },
            ],
        });
    };

    const openModal = () => HSOverlay.open("#hs-presence-modal");
    const closeModal = () => HSOverlay.close("#hs-presence-modal");
    const setModalTitle = (title) => $("#hs-presence-modal-label").text(title);

    const toggleFormDisabled = (disabled) => {
        $("#presence-form")
            .find("input, textarea, button[type='submit']")
            .prop("disabled", disabled);
    };

    const updateModalTotal = () => {
        const total = DAYS.reduce(
            (sum, day) => sum + rawNumber($(`#${day}`).val()),
            0,
        );
        $("#modal-total").text(formatRupiah(total));
    };

    const fillForm = (data) => {
        currentEmployeeId = data.employee_id ?? data.employee?.id;

        $("#employee_id").val(currentEmployeeId);
        $("#week_of").val(currentWeekOf);

        DAYS.forEach((day) => $(`#${day}`).val(data[day] ?? 0));
        $("#notes").val(data.notes ?? "");

        const isDeleted = !!data.employee?.deleted_at;
        toggleFormDisabled(isDeleted);
        updateModalTotal();
    };

    const handleRowClick = async (employeeId) => {
        try {
            const response = await ApiProvider.get(
                route("presences.show", employeeId),
                { week_of: currentWeekOf },
            );

            const presence = response.data;
            setModalTitle(`Presence - ${presence.employee?.name ?? ""}`);
            fillForm(presence);
            openModal();
        } catch (error) {
            console.error("Fetch presence error:", error);
        }
    };

    const submitForm = async (submitter) => {
        const formData = new FormData(form);
        let payload = Object.fromEntries(formData.entries());
        payload = normalizeFormInputs(form, payload);

        DAYS.forEach((day) => {
            payload[day] = rawNumber(payload[day]);
        });
        payload.week_of = currentWeekOf;

        try {
            const response = await ApiProvider.put(
                route("presences.update", currentEmployeeId),
                payload,
            );
            Toast.success(
                "Success",
                response.message ?? "Presence updated successfully",
            );
            closeModal();
            reloadDatatable();
        } catch (error) {
            console.error("Update presence error:", error);
        } finally {
            stopLoading(submitter);
        }
    };

    const bindEvents = () => {
        $("#filter-week").on("change", function () {
            const value = $(this).val();
            if (!value) return;

            const monday = isoWeekToMonday(value);
            setCurrentWeek(monday);
            reloadDatatable();
        });

        $(document).on("click", "#presences-datatable tbody tr", function () {
            const rowData = datatable.row(this).data();
            if (!rowData) return;
            handleRowClick(rowData.employee_id);
        });

        $(document).on("input", ".day-input", updateModalTotal);

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const submitter = e.submitter;
            if (submitter?.hasAttribute("data-button-loading"))
                startLoading(submitter);
            await submitForm(submitter);
        });

        document
            .getElementById("hs-presence-modal")
            .addEventListener("close.hs.overlay", () => {
                form.reset();
                toggleFormDisabled(false);
                currentEmployeeId = null;
            });
    };

    return {
        init() {
            form = document.getElementById("presence-form");

            const today = new Date();
            const monday = new Date(today);
            const day = monday.getDay();
            const diff = day === 0 ? -6 : 1 - day;
            monday.setDate(monday.getDate() + diff);

            setCurrentWeek(monday);
            $("#filter-week").val(dateToIsoWeek(monday));

            initDataTable();
            bindEvents();
        },
    };
})();

$(function () {
    PageScript.init();
});
