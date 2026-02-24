import { initLucide } from "@/utils/lucide";

export default function initDatatable({
    table,
    ajax,
    columns,
    pageLength = 10,
    order = [],
    filterSelector = null,
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
        order,
        drawCallback() {
            initLucide();

            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        },
    });

    // Search handler
    $("#dt-search").on("keyup", function () {
        datatable.search(this.value).draw();
    });

    // Length change handler
    $("#dt-length").on("change", function () {
        datatable.page.len(this.value).draw();
    });

    // Global filter handler
    if (filterSelector) {
        $(document).on("change", filterSelector, function () {
            datatable.ajax.reload(null, false);
        });
    }

    // Pagination click handler
    $(document).on("click", "#dt-pagination button[data-page]", function () {
        datatable.page($(this).data("page")).draw("page");
    });

    // Render pagination using templates
    const renderPagination = () => {
        const info = datatable.page.info();
        const container = $("#dt-pagination");
        container.empty();

        const current = info.page;
        const total = info.pages;
        const last = total - 1;

        // Clone templates
        const btnTemplate = document.getElementById(
            "dt-pagination-btn-template",
        );
        const ellipsisTemplate = document.getElementById(
            "dt-pagination-ellipsis-template",
        );
        const prevTemplate = document.getElementById(
            "dt-pagination-prev-template",
        );
        const nextTemplate = document.getElementById(
            "dt-pagination-next-template",
        );

        // Helper function to create button from template
        const createBtn = (page, label = null, isCurrent = false) => {
            const btn = btnTemplate.content
                .cloneNode(true)
                .querySelector("button");
            btn.dataset.page = page;
            btn.textContent = label ?? page + 1;

            if (isCurrent) {
                btn.classList.add(
                    "bg-(--color-primary)",
                    "text-(--color-light)",
                );
                btn.setAttribute("aria-current", "page");
            } else {
                btn.classList.add(
                    "border",
                    "border-transparent",
                    "text-(--color-dark)",
                    "dark:text-(--color-light)",
                    "hover:bg-(--color-light-gray)",
                    "dark:hover:bg-(--color-dark-gray)",
                );
            }

            return btn;
        };

        // Prev button
        const prevBtn = prevTemplate.content
            .cloneNode(true)
            .querySelector("button");
        prevBtn.dataset.page = current - 1;
        if (current === 0) {
            prevBtn.classList.add("opacity-50", "cursor-not-allowed");
            prevBtn.disabled = true;
        } else {
            prevBtn.classList.add("hover:bg-(--color-light-gray)");
        }
        container.append(prevBtn);

        // First page
        container.append(createBtn(0, null, current === 0));

        // Calculate middle pages
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

        // Start ellipsis
        if (start > 1) {
            container.append(ellipsisTemplate.content.cloneNode(true));
        }

        // Middle pages
        for (let i = start; i <= end; i++) {
            if (i > 0 && i < last) {
                container.append(createBtn(i, null, i === current));
            }
        }

        // End ellipsis
        if (end < last - 1) {
            container.append(ellipsisTemplate.content.cloneNode(true));
        }

        // Last page
        if (last > 0) {
            container.append(createBtn(last, total, current === last));
        }

        // Next button
        const nextBtn = nextTemplate.content
            .cloneNode(true)
            .querySelector("button");
        nextBtn.dataset.page = current + 1;
        if (current === last) {
            nextBtn.classList.add("opacity-50", "cursor-not-allowed");
            nextBtn.disabled = true;
        } else {
            nextBtn.classList.add("hover:bg-(--color-light-gray)");
        }
        container.append(nextBtn);

        // Update info text
        $("#dt-info").text(
            `Showing ${info.start + 1} – ${info.end} of ${info.recordsTotal}`,
        );

        initLucide();
    };

    datatable.on("draw", renderPagination);

    return datatable;
}
