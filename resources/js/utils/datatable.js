import { initLucide } from "@/utils/lucide";

export default function initDatatable({
    table,
    ajax,
    columns,
    pageLength = 10,
}) {
    if (!document.querySelector(table)) return;

    const datatable = $(table).DataTable({
        dom: "t",
        paging: true,
        pageLength,
        lengthChange: false,
        info: false,
        processing: true,
        serverSide: false,
        ajax,
        columns,
        drawCallback() {
            initLucide();

            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        },
    });

    $("#dt-search").on("keyup", function () {
        datatable.search(this.value).draw();
    });

    $("#dt-length").on("change", function () {
        datatable.page.len(this.value).draw();
    });

    $(document).on("click", "#dt-pagination button[data-page]", function () {
        datatable.page($(this).data("page")).draw("page");
    });

    const renderPagination = () => {
        const info = datatable.page.info();
        const container = $("#dt-pagination");

        container.empty();

        const current = info.page;
        const total = info.pages;
        const last = total - 1;

        const baseBtn =
            "min-h-9.5 min-w-9.5 flex justify-center items-center text-sm rounded-lg " +
            "focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none";

        const createBtn = (page, label = null) => `
            <button
                type="button"
                class="${baseBtn}
                    ${
                        page === current
                            ? "bg-(--color-primary) text-(--color-light)"
                            : "border border-transparent text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-light-gray) dark:hover:bg-(--color-dark-gray)"
                    } cursor-pointer"
                data-page="${page}"
                ${page === current ? 'aria-current="page"' : ""}
            >
                ${label ?? page + 1}
            </button>
        `;

        const ellipsis = `
            <span class="min-h-9.5 min-w-9.5 flex justify-center items-center text-sm text-(--color-gray)">
                …
            </span>
        `;

        // Prev
        container.append(`
            <button
                type="button"
                class="${baseBtn} border border-transparent
                    ${
                        current === 0
                            ? "opacity-50 cursor-not-allowed"
                            : "hover:bg-(--color-light-gray)"
                    } cursor-pointer"
                ${current === 0 ? "disabled" : ""}
                data-page="${current - 1}"
                aria-label="Previous"
            >
                <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/>
                </svg>
            </button>
        `);

        // Page 1
        container.append(createBtn(0));

        let start, end;

        if (current <= 3) {
            start = 1;
            end = 4;
        } else if (current >= last - 3) {
            start = last - 4;
            end = last - 1;
        } else {
            start = current - 1;
            end = current + 1;
        }

        if (start > 1) container.append(ellipsis);

        for (let i = start; i <= end; i++) {
            if (i > 0 && i < last) {
                container.append(createBtn(i));
            }
        }

        if (end < last - 1) container.append(ellipsis);

        if (last > 0) container.append(createBtn(last, total));

        // Next
        container.append(`
            <button
                type="button"
                class="${baseBtn} border border-transparent
                    ${
                        current === last
                            ? "opacity-50 cursor-not-allowed"
                            : "hover:bg-(--color-light-gray)"
                    } cursor-pointer"
                ${current === last ? "disabled" : ""}
                data-page="${current + 1}"
                aria-label="Next"
            >
                <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                </svg>
            </button>
        `);

        // Info
        $("#dt-info").text(
            `Showing ${info.start + 1} – ${info.end} of ${info.recordsTotal}`,
        );
    };

    datatable.on("draw", renderPagination);

    return datatable;
}
