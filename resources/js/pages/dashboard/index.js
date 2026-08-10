import initClock from "./clock";
import { renderStats } from "./stats";
import { initCharts, updateCharts } from "./charts";
import { renderPresences } from "./presence";
import { initFabricsTable } from "./fabrics-table";
import { initLatestSablonsTable, reloadLatestSablonsTable } from "./table";
import { currentIsoWeek, nextIsoWeek } from "@/utils/week";

const PageScript = (function () {
    const getUrlParams = () => new URLSearchParams(window.location.search);

    const applyFiltersFromUrl = () => {
        const params = getUrlParams();

        $("#filter-week-start").val(
            params.get("week_start") || currentIsoWeek(),
        );
        $("#filter-week-end").val(params.get("week_end") || nextIsoWeek());
    };

    const syncUrl = () => {
        const params = getUrlParams();
        params.set("week_start", $("#filter-week-start").val());
        params.set("week_end", $("#filter-week-end").val());

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState({}, "", newUrl);
    };

    const setDefaultWeekFilters = () => {
        $("#filter-week-start").val(currentIsoWeek());
        $("#filter-week-end").val(nextIsoWeek());
        syncUrl();
    };

    const fetchData = () => {
        $.ajax({
            url: route("dashboard"),
            method: "GET",
            dataType: "json",
            data: {
                week_start: $("#filter-week-start").val(),
                week_end: $("#filter-week-end").val(),
            },
            success(data) {
                renderStats(data.stats);
                updateCharts(data.charts);
                renderPresences(data.presences);
            },
            error(err) {
                console.error("Dashboard fetch error:", err);
            },
        });
    };

    const bindEvents = () => {
        $(document).on(
            "change",
            "#filter-week-start, #filter-week-end",
            function () {
                syncUrl();
                fetchData();
                reloadLatestSablonsTable();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            setDefaultWeekFilters();
            fetchData();
            reloadLatestSablonsTable();
        });
    };

    return {
        init() {
            applyFiltersFromUrl();
            syncUrl();

            initClock();
            initCharts();
            initFabricsTable();
            initLatestSablonsTable();
            bindEvents();
            fetchData();
        },
    };
})();

$(function () {
    PageScript.init();
});
