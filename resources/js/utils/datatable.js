import { initLucide } from "@/utils/lucide";

export default function initDatatable({
    table,
    ajax,
    columns,
    pageLength = parseInt(document.getElementById("dt-length")?.value) || 10,
    order = [],
    filterSelector = null,
    rowClickRoute = null,
    onRowClick = null,
}) {
    if (!document.querySelector(table)) return;

    const datatable = $(table).DataTable({
        dom: "t",
        paging: true,
        pageLength,
        lengthChange: false,
        info: false,
        processing: true,
        language: {
            processing: `
                <div class="dt-overlay-loader">
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative">
                            <div class="size-12 rounded-full border-4 border-(--color-primary)/20"></div>
                            <div class="size-12 rounded-full border-4 border-transparent border-t-(--color-primary) animate-spin absolute inset-0"></div>
                        </div>

                        <div class="text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                            Loading data...
                        </div>
                    </div>
                </div>
            `,
        },
        serverSide: false,
        ajax,
        columns,
        order,
        drawCallback() {
            initLucide();
            const rows = $(`${table} tbody tr`);
            rows.addClass(
                "hover:!bg-(--color-light-gray) dark:hover:!bg-(--color-dark-slate) cursor-pointer transition",
            );

            rows.off("click");

            if (rowClickRoute || onRowClick) {
                rows.on("click", function (e) {
                    if (
                        $(e.target).closest(
                            "button, a, .hs-dropdown, .hs-dropdown-menu",
                        ).length
                    ) {
                        return;
                    }

                    const rowData = datatable.row(this).data();

                    if (!rowData?.id) return;

                    if (onRowClick) {
                        onRowClick(rowData, this);
                        return;
                    }

                    if (rowClickRoute) {
                        window.location.href = rowClickRoute(rowData);
                    }
                });
            }

            if (window.HSStaticMethods) {
                window.HSStaticMethods.autoInit();
            }
        },
    });

    // Search handler
    let searchTimeout = null;

    const searchInput = $("#dt-search");
    const clearBtn = $("#dt-search-clear");

    searchInput.on("keyup", function () {
        const value = this.value;

        // toggle clear button
        if (value.length > 0) {
            clearBtn.removeClass("hidden").addClass("flex");
        } else {
            clearBtn.removeClass("flex").addClass("hidden");
        }

        clearTimeout(searchTimeout);

        searchTimeout = setTimeout(() => {
            datatable.search(value).draw();
        }, 500);
    });

    // Clear search
    clearBtn.on("click", function () {
        searchInput.val("");

        clearBtn.removeClass("flex").addClass("hidden");

        datatable.search("").draw();

        searchInput.trigger("focus");
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
