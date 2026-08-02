import ApiProvider from "@/utils/api-provider";
import initDatatable from "@/utils/datatable";
import normalizeFormInputs from "@/utils/normalize-form";
import { startLoading, stopLoading } from "@/utils/button-loading";
import RupiahInput from "@/utils/rupiah-input";
import BulkGenerateModal from "./bulk-generate";
import {
    isoWeekToMonday,
    dateToIsoWeek,
    toDateStr,
    currentMonday,
    formatShortDate,
} from "@/utils/week";

const DAYS = [
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday",
    "sunday",
];

const GENERATE_DAYS = [
    "monday",
    "tuesday",
    "wednesday",
    "thursday",
    "friday",
    "saturday",
];

const DAY_LABELS = {
    monday: "Senin",
    tuesday: "Selasa",
    wednesday: "Rabu",
    thursday: "Kamis",
    friday: "Jumat",
    saturday: "Sabtu",
    sunday: "Minggu",
};

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

    // NOTE: isoWeekToMonday, dateToIsoWeek, toDateStr now imported from @/utils/week

    const updateTheadDates = (mondayDate) => {
        DAYS.forEach((day, index) => {
            const date = new Date(mondayDate);
            date.setDate(mondayDate.getDate() + index);
            $(`#th-${day} .th-date`).text(formatShortDate(date));
        });
    };

    const updateModalDates = (mondayDate) => {
        DAYS.forEach((day, index) => {
            const date = new Date(mondayDate);
            date.setDate(mondayDate.getDate() + index);
            $(`#${day}-date`).text(`(${formatShortDate(date)})`);
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
                dataSrc: function (json) {
                    if (json.dates)
                        updateTheadDates(new Date(json.dates.monday));
                    return json.data;
                },
                data: function (d) {
                    d.filter = $("#filter-presences").val();
                    d.week_of = currentWeekOf;
                },
            },
            columns: [
                {
                    width: "10%",
                    data: "employee_name",
                    render: (name, type, row) =>
                        row.is_deleted
                            ? `<span class="text-(--color-red) line-through">${name}</span>`
                            : name,
                },
                {
                    width: "10%",
                    data: "monday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "tuesday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "wednesday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "thursday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "friday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "saturday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "sunday",
                    className: "text-center",
                    render: formatRupiah,
                },
                {
                    width: "10%",
                    data: "total",
                    className: "text-center font-semibold",
                    render: formatRupiah,
                },
            ],
        });
    };

    const openModal = () => HSOverlay.open("#hs-presence-modal");
    const closeModal = () => HSOverlay.close("#hs-presence-modal");
    const setModalTitle = (title) => $("#hs-presence-modal-label").text(title);

    const toggleFormDisabled = (disabled) => {
        $("#presence-form")
            .find("input, textarea, button[type='submit'], #btn-generate")
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

        DAYS.forEach((day) => {
            const input = document.getElementById(day);
            input.value = data[day] ?? 0;
            RupiahInput.refresh(input);
        });

        $("#notes").val(data.notes ?? "");
        $("#generate_value").val(0);
        RupiahInput.refresh(document.getElementById("generate_value"));

        updateModalDates(
            isoWeekToMonday(dateToIsoWeek(new Date(currentWeekOf))),
        );

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

    const handleQuickAmount = (btn) => {
        const target = document.querySelector(btn.dataset.target);
        if (!target) return;

        target.value = btn.dataset.quickAmount;
        target.dispatchEvent(new Event("input", { bubbles: true }));
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

    const generateValues = () => {
        const value = rawNumber($("#generate_value").val());

        GENERATE_DAYS.forEach((day) => {
            const input = document.getElementById(day);
            input.value = value;
            RupiahInput.refresh(input);
        });

        updateModalTotal();
    };

    const bindEvents = () => {
        $("#filter-week").on("change", function () {
            const value = $(this).val();
            if (!value) return;

            const monday = isoWeekToMonday(value);
            setCurrentWeek(monday);
            reloadDatatable();
        });

        $(document).on("click", "[data-quick-amount]", function () {
            handleQuickAmount(this);
        });

        $(document).on("click", "#presences-datatable tbody tr", function () {
            const rowData = datatable.row(this).data();
            if (!rowData) return;
            handleRowClick(rowData.employee_id);
        });

        $(document).on("input", ".day-input", updateModalTotal);

        $(document).on("click", "#btn-generate", function (e) {
            e.preventDefault();
            generateValues();
        });

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

            const monday = currentMonday();
            setCurrentWeek(monday);
            $("#filter-week").val(dateToIsoWeek(monday));

            initDataTable();
            bindEvents();

            BulkGenerateModal.init({
                currentWeekOf: () => new Date(currentWeekOf),
                onGenerated: reloadDatatable,
            });
        },
    };
})();

$(function () {
    PageScript.init();
});
