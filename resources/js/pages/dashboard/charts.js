import ApexCharts from "apexcharts";

let statusSablonChart;
let sablonPerDayChart;
let topSupplierChart;

export const initCharts = () => {
    const seriesLabel =
        window.langDashboard?.chart?.total_sablon ?? "Total Sablon";

    statusSablonChart = new ApexCharts(
        document.querySelector("#chart-status-sablon"),
        {
            chart: { type: "donut", height: 300 },
            series: [],
            labels: [],
            colors: ["#e2a156", "#63d6da", "#66a76b", "#d44e4e"],
            legend: { position: "bottom" },
        },
    );
    statusSablonChart.render();

    sablonPerDayChart = new ApexCharts(
        document.querySelector("#chart-sablon-per-day"),
        {
            chart: { type: "bar", height: 300, toolbar: { show: false } },
            series: [{ name: seriesLabel, data: [] }],
            xaxis: { categories: [] },
            colors: ["#6d9886"],
        },
    );
    sablonPerDayChart.render();

    topSupplierChart = new ApexCharts(
        document.querySelector("#chart-top-supplier"),
        {
            chart: { type: "bar", height: 300, toolbar: { show: false } },
            series: [{ name: seriesLabel, data: [] }],
            xaxis: { categories: [] },
            plotOptions: { bar: { horizontal: true } },
            colors: ["#63d6da"],
        },
    );
    topSupplierChart.render();
};

export const updateCharts = (charts) => {
    if (!charts) return;
    const seriesLabel =
        window.langDashboard?.chart?.total_sablon ?? "Total Sablon";

    if (charts.status_sablon) {
        statusSablonChart.updateOptions({
            labels: charts.status_sablon.labels,
        });
        statusSablonChart.updateSeries(charts.status_sablon.series);
    }

    if (charts.sablon_per_day) {
        sablonPerDayChart.updateOptions({
            xaxis: { categories: charts.sablon_per_day.categories },
        });
        sablonPerDayChart.updateSeries([
            { name: seriesLabel, data: charts.sablon_per_day.series },
        ]);
    }

    if (charts.top_supplier) {
        topSupplierChart.updateOptions({
            xaxis: { categories: charts.top_supplier.categories },
        });
        topSupplierChart.updateSeries([
            { name: seriesLabel, data: charts.top_supplier.series },
        ]);
    }
};
