import ApiProvider from "@/utils/api-provider";

const PageScript = (function () {
    const tableSelector = "#users-datatable";
    let datatable;

    const initDatatable = () => {
        if (!document.querySelector(tableSelector)) return;

        datatable = $(tableSelector).DataTable({
            dom: "t",
            paging: true,
            pageLength: 10,
            lengthChange: false,
            info: false,
            processing: true,
            serverSide: false,
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
        datatable.on("draw", function () {
            renderPagination();
        });
    };

    const renderPagination = () => {
        const info = datatable.page.info();
        const container = $("#dt-pagination");

        container.empty();

        const current = info.page;
        const total = info.pages;
        const last = total - 1;

        const createBtn = (page, label = null) => `
            <button
                class="px-3 py-1 text-sm rounded-lg border
                    ${
                        page === current
                            ? "bg-(--color-primary) text-white"
                            : "hover:bg-(--color-light-gray)"
                    }"
                data-page="${page}">
                ${label ?? page + 1}
            </button>
        `;

        const ellipsis = `
            <span class="px-2 py-1 text-sm text-(--color-gray)">…</span>
        `;

        // ===== Prev =====
        container.append(`
            <button
                class="px-3 py-1 text-sm rounded-lg border
                    ${current === 0 ? "opacity-50 cursor-not-allowed" : "hover:bg-(--color-light-gray)"}"
                ${current === 0 ? "disabled" : ""}
                data-page="${current - 1}">
                Prev
            </button>
        `);

        // ===== Page 1 =====
        container.append(createBtn(0));

        let start, end;

        if (current <= 3) {
            // Page 1–4
            start = 1;
            end = 4;
        } else if (current >= last - 3) {
            // Page akhir
            start = last - 4;
            end = last - 1;
        } else {
            // Tengah
            start = current - 1;
            end = current + 1;
        }

        // ===== Left Ellipsis =====
        if (start > 1) {
            container.append(ellipsis);
        }

        // ===== Middle Pages =====
        for (let i = start; i <= end; i++) {
            if (i > 0 && i < last) {
                container.append(createBtn(i));
            }
        }

        // ===== Right Ellipsis =====
        if (end < last - 1) {
            container.append(ellipsis);
        }

        // ===== Last Page =====
        if (last > 0) {
            container.append(createBtn(last, total));
        }

        // ===== Next =====
        container.append(`
            <button
                class="px-3 py-1 text-sm rounded-lg border
                    ${current === last ? "opacity-50 cursor-not-allowed" : "hover:bg-(--color-light-gray)"}"
                ${current === last ? "disabled" : ""}
                data-page="${current + 1}">
                Next
            </button>
        `);

        $("#dt-info").text(
            `Showing ${info.start + 1}–${info.end} of ${info.recordsTotal}`
        );
    };

    return {
        init() {
            initDatatable();

            $("#dt-search").on("keyup", function () {
                datatable.search(this.value).draw();
            });

            $("#dt-length").on("change", function () {
                datatable.page.len(this.value).draw();
            });

            $(document).on(
                "click",
                "#dt-pagination button[data-page]",
                function () {
                    const page = $(this).data("page");
                    datatable.page(page).draw("page");
                },
            );
        },
    };
})();

$(function () {
    PageScript.init();
});
