const pad = (n) => String(n).padStart(2, "0");

const formatDate = (date) => {
    const locale = document.documentElement.lang === "id" ? "id-ID" : "en-US";
    return date.toLocaleDateString(locale, {
        weekday: "long",
        day: "numeric",
        month: "long",
        year: "numeric",
    });
};

const formatTime = (date) =>
    `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;

const tick = () => {
    const now = new Date();
    $("#dashboard-clock-date").text(formatDate(now));
    $("#dashboard-clock-time").text(formatTime(now));
};

const initClock = () => {
    if (!$("#dashboard-clock-time").length) return;
    tick();
    setInterval(tick, 1000);
};

export default initClock;
