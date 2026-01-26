import ApiProvider from "@/utils/api-provider";

const PageScript = (function () {
    const tableSelector = "#users-datatable";
    let datatable;

    const initDatatable = () => {
        if (!document.querySelector(tableSelector)) return;

        datatable = $(tableSelector).DataTable({
            processing: true,
            ajax: {
                url: route("users.index"),
                method: "GET",
                dataSrc: "data",
            },
            columns: [
                {
                    data: "name",
                    className: "px-4 py-3",
                },
                {
                    data: "email",
                    className: "px-4 py-3",
                },
                {
                    data: "roles",
                    className: "px-4 py-3",
                    render(data) {
                        return data
                            .map(
                                (role) =>
                                    `<span class="badge">${role.name}</span>`,
                            )
                            .join("");
                    },
                },
                {
                    data: "id",
                    orderable: false,
                    searchable: false,
                    className: "px-4 py-3 text-right",
                    render(id) {
                        return `
                            <a href="${route("users.edit", id)}"
                               class="text-(--color-primary) hover:underline">
                               Edit
                            </a>
                        `;
                    },
                },
            ],
            drawCallback() {
                // Lucide → aman & rapi
                if (window.lucide) {
                    lucide.createIcons();
                }
            },
        });
    };

    return {
        init() {
            initDatatable();
        },
    };
})();

$(function () {
    PageScript.init();
});
