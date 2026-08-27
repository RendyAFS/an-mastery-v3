import initClock from "./clock";
import { renderStats } from "./stats";
import { initCharts, updateCharts } from "./charts";
import { renderPresences } from "./presence";
import { initFabricsTable } from "./fabrics-table";
import {
    initLatestSablonsTable,
    reloadLatestSablonsTable,
} from "./latest-sablons-table";
import { getFlatpickrInstance } from "@/utils/flatpickr-init";
import { getCenteredWeekRange } from "@/utils/week";

const PageScript = (function () {
    const getUrlParams = () => new URLSearchParams(window.location.search);

    const getDefaultRange = () => getCenteredWeekRange(1, 1);

    const applyFiltersFromUrl = () => {
        const params = getUrlParams();
        const instance = getFlatpickrInstance("filter-date-range");

        const start = params.get("week_start");
        const end = params.get("week_end");

        if (start && end) {
            instance.setDate([start, end], true);
        } else {
            instance.setDate(getDefaultRange(), true);
        }
    };

    const syncUrl = () => {
        const params = getUrlParams();
        params.set("week_start", $("#filter-date-range_start").val());
        params.set("week_end", $("#filter-date-range_end").val());

        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.replaceState({}, "", newUrl);
    };

    const setDefaultRangeFilters = () => {
        const instance = getFlatpickrInstance("filter-date-range");
        instance.setDate(getDefaultRange(), true);
    };

    const fetchData = () => {
        $.ajax({
            url: route("dashboard"),
            method: "GET",
            dataType: "json",
            data: {
                week_start: $("#filter-date-range_start").val(),
                week_end: $("#filter-date-range_end").val(),
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
            "flatpickr:range-change",
            "#filter-date-range",
            function (e) {
                if (!e.detail.start || !e.detail.end) return;
                syncUrl();
                fetchData();
                reloadLatestSablonsTable();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            setDefaultRangeFilters();
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
