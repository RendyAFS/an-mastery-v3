import initDatatable from "@/utils/datatable";

const statusBadgeMap = {
    ON_PROGRESS: "badge-warning",
    DONE: "badge-info",
    DELIVERED: "badge-success",
    RETURNED: "badge-danger",
};

let datatable;

export const initLatestSablonsTable = () => {
    datatable = initDatatable({
        table: "#dashboard-sablons-datatable",
        pageLength: 10,
        rowClickRoute: (row) => route("sablons.edit", row.id),
        ajax: {
            url: route("dashboard.latest-sablons"),
            method: "GET",
            dataSrc: "data",
            data: function (d) {
                d.week_start = $("#filter-week-start").val();
                d.week_end = $("#filter-week-end").val();
            },
        },
        columns: [
            { data: "supplier", width: "20%" },
            { data: "image_fabric", width: "20%" },
            { data: "date_sablon", width: "15%" },
            {
                data: "status",
                width: "20%",
                render(status, type, row) {
                    const cls = statusBadgeMap[status] ?? "badge-primary";
                    return `<span class="badge ${cls}">${row.status_label ?? "-"}</span>`;
                },
            },
            {
                data: "total_sablon_formated",
                width: "25%",
                className: "text-right",
            },
        ],
    });
};

export const reloadLatestSablonsTable = () => {
    datatable?.ajax.reload(null, false);
};
