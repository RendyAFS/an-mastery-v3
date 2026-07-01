import AirDatepicker from "air-datepicker";
import "air-datepicker/air-datepicker.css";
import localeEn from "air-datepicker/locale/en";

/**
 * air-datepicker doesn't ship an Indonesian locale, so it's defined here.
 * Add more locales to this object as needed.
 */
const localeId = {
    days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
    daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
    daysMin: ["Mg", "Sn", "Sl", "Rb", "Km", "Jm", "Sb"],
    months: [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ],
    monthsShort: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "Mei",
        "Jun",
        "Jul",
        "Agu",
        "Sep",
        "Okt",
        "Nov",
        "Des",
    ],
    today: "Hari ini",
    clear: "Bersihkan",
    dateFormat: "dd/MM/yyyy",
    timeFormat: "HH:mm",
    firstDay: 1,
};

const LOCALES = { en: localeEn, id: localeId };

const DEFAULTS = {
    dateFormat: "dd/MM/yyyy",
    altFieldDateFormat: "yyyy-MM-dd",
    locale: "en",
    firstDay: 1,
    autoClose: true,
    position: "bottom left",
    multipleDatesSeparator: ", ",
};

/** el -> AirDatepicker instance, mirrors HSSelect.getInstance() pattern */
const instances = new Map();

const pad = (n) => String(n).padStart(2, "0");

const formatISO = (date) =>
    date
        ? `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`
        : "";

const parseISO = (value) => {
    if (!value) return null;
    const [y, m, d] = value.split("-").map(Number);
    if (!y || !m || !d) return null;
    const date = new Date(y, m - 1, d);
    return Number.isNaN(date.getTime()) ? null : date;
};

const getWeekRange = (date, firstDay = 1) => {
    const d = new Date(date);
    const day = d.getDay();
    const diff = (day < firstDay ? 7 : 0) + day - firstDay;

    const start = new Date(d);
    start.setDate(d.getDate() - diff);
    start.setHours(0, 0, 0, 0);

    const end = new Date(start);
    end.setDate(start.getDate() + 6);

    return [start, end];
};

const dispatchChange = (el) => {
    if (el) el.dispatchEvent(new Event("change", { bubbles: true }));
};

const setHiddenValue = (el, value) => {
    if (!el) return;
    if (el.value === (value ?? "")) return;
    el.value = value ?? "";
    dispatchChange(el);
};

const hiddenFieldOf = (name) =>
    name ? document.getElementById(`${name}-hidden`) : null;

const toggleClearButton = (el) => {
    const btn = document.querySelector(`[data-clear-datepicker="${el.id}"]`);
    if (!btn) return;
    btn.style.display = el.value ? "inline-flex" : "none";
};

function initOne(el) {
    if (el.dataset.datepickerInitialized === "1") return;

    const mode = el.dataset.mode || "single";
    const isRange = mode === "range";
    const isWeek = mode === "week";
    const isMultiple = mode === "multiple";

    let options = {};
    try {
        options = JSON.parse(el.dataset.options || "{}");
    } catch (e) {
        console.error("Invalid data-options JSON for datepicker", el, e);
    }
    options = { ...DEFAULTS, ...options };

    const startField = hiddenFieldOf(el.dataset.startField);
    const endField = hiddenFieldOf(el.dataset.endField);

    // Build initial selectedDates from whatever is already in the hidden field(s)
    // (old() values on validation errors, edit forms, filters restored from query string, etc.)
    let selectedDates = [];

    if (isWeek) {
        const anchor = parseISO(startField?.value);
        if (anchor) selectedDates = getWeekRange(anchor, options.firstDay);
    } else if (isRange) {
        const s = parseISO(startField?.value);
        const e = parseISO(endField?.value);
        selectedDates = [s, e].filter(Boolean);
    } else if (isMultiple) {
        selectedDates = (startField?.value || "")
            .split(",")
            .map((v) => parseISO(v.trim()))
            .filter(Boolean);
    } else {
        const s = parseISO(startField?.value);
        if (s) selectedDates = [s];
    }

    const config = {
        locale: LOCALES[options.locale] || localeEn,
        dateFormat: options.dateFormat,
        firstDay: options.firstDay,
        minDate: options.minDate
            ? parseISO(options.minDate) || options.minDate
            : undefined,
        maxDate: options.maxDate
            ? parseISO(options.maxDate) || options.maxDate
            : undefined,
        timepicker: !!options.timepicker,
        timeFormat: options.timeFormat || undefined,
        inline: !!options.inline,
        autoClose: options.autoClose,
        position: options.position,
        container: options.container || undefined,
        buttons: options.buttons || false,
        range: isRange || isWeek,
        multipleDates: isMultiple,
        multipleDatesSeparator: options.multipleDatesSeparator,
        selectedDates: selectedDates.length ? selectedDates : false,

        onSelect({ date, datepicker }) {
            const dates = Array.isArray(date) ? date : [date].filter(Boolean);

            if (isWeek) {
                const anchor = dates[0];

                if (!anchor) {
                    setHiddenValue(startField, "");
                    setHiddenValue(endField, "");
                    return;
                }

                const [start, end] = getWeekRange(anchor, options.firstDay);

                setHiddenValue(startField, formatISO(start));
                setHiddenValue(endField, formatISO(end));

                el.value = `${start.toLocaleDateString("id-ID")} - ${end.toLocaleDateString("id-ID")}`;

                const alreadyFullWeek =
                    dates.length === 2 &&
                    formatISO(dates[0]) === formatISO(start) &&
                    formatISO(dates[1]) === formatISO(end);

                // Snap the visual selection to the full Mon-Sun range, silently
                // so we don't re-enter this callback.
                if (!alreadyFullWeek) {
                    datepicker.selectDate([start, end], { silent: true });
                }

                if (options.autoClose) datepicker.hide();
                return;
            }

            if (isRange) {
                setHiddenValue(startField, dates[0] ? formatISO(dates[0]) : "");
                setHiddenValue(endField, dates[1] ? formatISO(dates[1]) : "");
                return;
            }

            if (isMultiple) {
                setHiddenValue(startField, dates.map(formatISO).join(","));
                return;
            }

            setHiddenValue(startField, dates[0] ? formatISO(dates[0]) : "");
        },
    };

    Object.keys(config).forEach((key) => {
        if (config[key] === undefined) delete config[key];
    });

    const dp = new AirDatepicker(el, config);

    instances.set(el, dp);
    el.dataset.datepickerInitialized = "1";

    toggleClearButton(el);
    el.addEventListener("change", () => toggleClearButton(el));
}

/**
 * Initialize every [data-air-datepicker] element inside `container`.
 * Safe to call repeatedly (e.g. after loading a modal via ajax) -- already
 * initialized elements are skipped.
 */
export function initDatepicker(container = document) {
    container.querySelectorAll?.("[data-air-datepicker]").forEach(initOne);
}

export function getInstance(el) {
    if (typeof el === "string") el = document.getElementById(el);
    return el ? instances.get(el) : null;
}

export function destroyDatepicker(el) {
    if (typeof el === "string") el = document.getElementById(el);
    const inst = el && instances.get(el);
    if (!inst) return;

    inst.destroy();
    instances.delete(el);
    delete el.dataset.datepickerInitialized;
}

document.addEventListener("DOMContentLoaded", () => initDatepicker(document));

// Re-init any datepickers inside Preline overlays/modals when they open
document.addEventListener("open.hs.overlay", (e) => {
    if (e.target) initDatepicker(e.target);
});

// Clear button, mirrors custom-select.js's data-clear-select behavior
document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-clear-datepicker]");
    if (!btn) return;

    const el = document.getElementById(
        btn.getAttribute("data-clear-datepicker"),
    );
    if (!el) return;

    getInstance(el)?.clear();

    setHiddenValue(hiddenFieldOf(el.dataset.startField), "");
    setHiddenValue(hiddenFieldOf(el.dataset.endField), "");
    el.value = "";

    btn.style.display = "none";
});

export default {
    init: initDatepicker,
    getInstance,
    destroy: destroyDatepicker,
};
