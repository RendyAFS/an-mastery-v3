import ApexCharts from "apexcharts";

let statusSablonChart;
let sablonPerDayChart;
let topSupplierChart;
let statusSablonSuppliers = [];

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
            dataLabels: {
                formatter: (val, opts) => {
                    return opts.w.globals.series[opts.seriesIndex];
                },
            },
            tooltip: {
                theme: "light",
                custom: ({ series, seriesIndex, w }) => {
                    const label = w.globals.labels[seriesIndex];
                    const total = series[seriesIndex];
                    const color = w.globals.colors[seriesIndex];
                    const suppliers = statusSablonSuppliers[seriesIndex] ?? [];

                    const rows = suppliers.length
                        ? suppliers
                              .map(
                                  (s) => `
                            <div class="apexcharts-tooltip-y-group">
                                <span class="apexcharts-tooltip-text-y-label">${s.supplier}: </span>
                                <span class="apexcharts-tooltip-text-y-value">${s.total}</span>
                            </div>`,
                              )
                              .join("")
                        : `<div class="apexcharts-tooltip-y-group">
                            <span class="apexcharts-tooltip-text-y-label">-</span>
                        </div>`;

                    return `
                        <div style="background:#ffffff;border-radius:5px;overflow:hidden;">
                            <div class="apexcharts-tooltip-title" style="background:#ECEFF1;color:#1f2937;">${label} (${total})</div>
                            <div class="apexcharts-tooltip-series-group apexcharts-active" style="display:flex;background:#ffffff;color:#1f2937;">
                                <span class="apexcharts-tooltip-marker" style="background-color:${color};"></span>
                                <div class="apexcharts-tooltip-text">
                                    ${rows}
                                </div>
                            </div>
                        </div>`;
                },
            },
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
            tooltip: {
                theme: "light",
                custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                    const label = w.globals.labels[dataPointIndex];
                    const value = series[seriesIndex][dataPointIndex];
                    const color = w.globals.colors[seriesIndex];
                    const name = w.globals.seriesNames[seriesIndex];

                    return `
                        <div style="background:#ffffff;border-radius:5px;overflow:hidden;">
                            <div class="apexcharts-tooltip-title" style="background:#ECEFF1;color:#1f2937;">${label}</div>
                            <div class="apexcharts-tooltip-series-group apexcharts-active" style="display:flex;background:#ffffff;color:#1f2937;">
                                <span class="apexcharts-tooltip-marker" style="background-color:${color};"></span>
                                <div class="apexcharts-tooltip-text">
                                    <div class="apexcharts-tooltip-y-group">
                                        <span class="apexcharts-tooltip-text-y-label">${name}: </span>
                                        <span class="apexcharts-tooltip-text-y-value">${value}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                },
            },
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
            tooltip: {
                theme: "light",
                custom: ({ series, seriesIndex, dataPointIndex, w }) => {
                    const label = w.globals.labels[dataPointIndex];
                    const value = series[seriesIndex][dataPointIndex];
                    const color = w.globals.colors[seriesIndex];
                    const name = w.globals.seriesNames[seriesIndex];

                    return `
                        <div style="background:#ffffff;border-radius:5px;overflow:hidden;">
                            <div class="apexcharts-tooltip-title" style="background:#ECEFF1;color:#1f2937;">${label}</div>
                            <div class="apexcharts-tooltip-series-group apexcharts-active" style="display:flex;background:#ffffff;color:#1f2937;">
                                <span class="apexcharts-tooltip-marker" style="background-color:${color};"></span>
                                <div class="apexcharts-tooltip-text">
                                    <div class="apexcharts-tooltip-y-group">
                                        <span class="apexcharts-tooltip-text-y-label">${name}: </span>
                                        <span class="apexcharts-tooltip-text-y-value">${value}</span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                },
            },
        },
    );
    topSupplierChart.render();
};

export const updateCharts = (charts) => {
    if (!charts) return;
    const seriesLabel =
        window.langDashboard?.chart?.total_sablon ?? "Total Sablon";

    if (charts.status_sablon) {
        statusSablonSuppliers = charts.status_sablon.suppliers ?? [];

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
