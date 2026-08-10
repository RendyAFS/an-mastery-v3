/**
 * Reusable week (ISO week) date helpers.
 * ISO week input format: "YYYY-Www" (e.g. "2026-W26"), matches <input type="week">.
 */

/**
 * Convert an ISO week string ("2026-W26") to its Monday date.
 * @param {string} isoWeekStr
 * @returns {Date}
 */
export const isoWeekToMonday = (isoWeekStr) => {
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

/**
 * Convert a Date to its ISO week string ("2026-W26").
 * @param {Date} date
 * @returns {string}
 */
export const dateToIsoWeek = (date) => {
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

/**
 * Format a Date as "YYYY-MM-DD" (local, no timezone shift).
 * @param {Date} date
 * @returns {string}
 */
export const toDateStr = (date) => {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, "0");
    const d = String(date.getDate()).padStart(2, "0");
    return `${y}-${m}-${d}`;
};

/**
 * Get the Monday of the current week (local time).
 * @returns {Date}
 */
export const currentMonday = () => {
    const today = new Date();
    const monday = new Date(today);
    const day = monday.getDay();
    const diff = day === 0 ? -6 : 1 - day;
    monday.setDate(monday.getDate() + diff);
    return monday;
};

/**
 * Get the current week as an ISO week string ("2026-W26").
 * @returns {string}
 */
export const currentIsoWeek = () => dateToIsoWeek(currentMonday());

/**
 * Get the Monday of next week (local time).
 * @returns {Date}
 */
export const nextMonday = () => {
    const monday = currentMonday();
    monday.setDate(monday.getDate() + 7);
    return monday;
};

/**
 * Get next week as an ISO week string ("2026-W27").
 * @returns {string}
 */
export const nextIsoWeek = () => dateToIsoWeek(nextMonday());

/**
 * Convert an ISO week string directly to a "YYYY-MM-DD" Monday date string.
 * Convenience wrapper for the common isoWeekToMonday -> toDateStr chain.
 * @param {string} isoWeekStr
 * @returns {string}
 */
export const isoWeekToDateStr = (isoWeekStr) =>
    toDateStr(isoWeekToMonday(isoWeekStr));

/**
 * Format a Date as "dd/mm" for compact display (e.g. table headers).
 * @param {Date} date
 * @returns {string}
 */
export const formatShortDate = (date) =>
    date.toLocaleDateString("id-ID", { day: "2-digit", month: "2-digit" });
