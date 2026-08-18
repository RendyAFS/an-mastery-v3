import { initLucide } from "@/utils/lucide";

export default function initDatatable({
    table,
    ajax,
    columns,
    pageLength,
    order = [],
    filterSelector = null,
    rowClickRoute = null,
    onRowClick = null,
}) {
    if (!document.querySelector(table)) return;

    const tableId = table.replace("#", "");

    if (!pageLength) {
        pageLength =
            parseInt(document.getElementById(`dt-length-${tableId}`)?.value) ||
            10;
    }

    const filterId = filterSelector
        ? filterSelector.startsWith("#") || filterSelector.startsWith(".")
            ? filterSelector
            : `#${filterSelector}-${tableId}`
        : null;
    const filterEl = filterId ? $(filterId) : null;

    const ajaxOptions = typeof ajax === "string" ? { url: ajax } : { ...ajax };
    const originalData = ajaxOptions.data;

    ajaxOptions.data = function (d) {
        if (typeof originalData === "function") {
            originalData(d);
        } else if (originalData) {
            Object.assign(d, originalData);
        }

        if (filterEl && filterEl.length) {
            d.filter = filterEl.val();
        }

        return d;
    };

    let isProcessing = false;

    const toggleControlsDisabled = (disabled) => {
        const controlsWrapper = document.querySelector(`[data-dt-controls="${tableId}"]`);
        if (controlsWrapper) {
            controlsWrapper.classList.toggle("pointer-events-none", disabled);
            controlsWrapper.classList.toggle("opacity-50", disabled);

            controlsWrapper.querySelectorAll("input, select, button, .btn-group-item").forEach((el) => {
                el.disabled = disabled;
            });

            controlsWrapper.querySelectorAll(".hs-select").forEach((el) => {
                el.classList.toggle("pointer-events-none", disabled);
                el.classList.toggle("opacity-50", disabled);
            });
        }

        // Fallback for individual elements
        const searchEl = document.getElementById(`dt-search-${tableId}`);
        const clearEl = document.getElementById(`dt-search-clear-${tableId}`);
        if (searchEl) {
            searchEl.disabled = disabled;
            searchEl.classList.toggle("pointer-events-none", disabled);
            searchEl.classList.toggle("opacity-50", disabled);
        }
        if (clearEl) {
            clearEl.disabled = disabled;
            clearEl.classList.toggle("pointer-events-none", disabled);
            clearEl.classList.toggle("opacity-50", disabled);
        }

        const lengthEl = document.getElementById(`dt-length-${tableId}`);
        if (lengthEl) {
            lengthEl.disabled = disabled;
            const hsWrapper = lengthEl.closest(".hs-select");
            if (hsWrapper) {
                hsWrapper.classList.toggle("pointer-events-none", disabled);
                hsWrapper.classList.toggle("opacity-50", disabled);
            }
        }

        if (filterId) {
            const filterDom = document.querySelector(filterId);
            if (filterDom) {
                filterDom.disabled = disabled;
                const hsWrapper = filterDom.closest(".hs-select");
                if (hsWrapper) {
                    hsWrapper.classList.toggle("pointer-events-none", disabled);
                    hsWrapper.classList.toggle("opacity-50", disabled);
                }
            }
        }

        const pagEl = document.getElementById(`dt-pagination-${tableId}`);
        if (pagEl) {
            pagEl.classList.toggle("pointer-events-none", disabled);
            pagEl.classList.toggle("opacity-50", disabled);
            pagEl.querySelectorAll("button").forEach((btn) => {
                btn.disabled = disabled;
            });
        }
    };

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
                        ${window.langDatatable?.[tableId]?.loading ?? "Loading data..."}
                    </div>
                </div>
            </div>
        `,
        },
        serverSide: false,
        ajax: ajaxOptions,
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
                            "button, a, .hs-dropdown, .hs-dropdown-menu, .toggle-active, label",
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
                window.HSStaticMethods.autoInit([
                    "dropdown",
                    "tooltip",
                    "overlay",
                    "select",
                    "copy-markup",
                    "remove-element",
                ]);
            }
        },
    });

    datatable.on("processing.dt", function (e, settings, processing) {
        isProcessing = processing;
        toggleControlsDisabled(processing);
    });

    let searchTimeout = null;

    const searchInput = $(`#dt-search-${tableId}`);
    const clearBtn = $(`#dt-search-clear-${tableId}`);
    const lengthSelect = $(`#dt-length-${tableId}`);
    const paginationContainer = $(`#dt-pagination-${tableId}`);
    const infoContainer = $(`#dt-info-${tableId}`);

    searchInput.on("keyup input", function () {
        if (isProcessing) return;

        const value = this.value;

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

    clearBtn.on("click", function () {
        if (isProcessing) return;

        searchInput.val("");

        clearBtn.removeClass("flex").addClass("hidden");

        datatable.search("").draw();

        searchInput.trigger("focus");
    });

    lengthSelect.on("change", function () {
        if (isProcessing) return;
        datatable.page.len(this.value).draw();
    });

    if (filterId) {
        $(document).on("change", filterId, function () {
            if (isProcessing) return;
            datatable.ajax.reload(null, false);
        });
    }

    paginationContainer.on("click", "button[data-page]", function () {
        if (isProcessing) return;
        datatable.page($(this).data("page")).draw("page");
    });

    const renderPagination = () => {
        const info = datatable.page.info();
        const container = paginationContainer;
        container.empty();

        const current = info.page;
        const total = info.pages;
        const last = total - 1;

        const btnTemplate = document.getElementById(
            `dt-pagination-btn-template-${tableId}`,
        );
        const ellipsisTemplate = document.getElementById(
            `dt-pagination-ellipsis-template-${tableId}`,
        );
        const prevTemplate = document.getElementById(
            `dt-pagination-prev-template-${tableId}`,
        );
        const nextTemplate = document.getElementById(
            `dt-pagination-next-template-${tableId}`,
        );

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

        container.append(createBtn(0, null, current === 0));

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

        if (start > 1) {
            container.append(ellipsisTemplate.content.cloneNode(true));
        }

        for (let i = start; i <= end; i++) {
            if (i > 0 && i < last) {
                container.append(createBtn(i, null, i === current));
            }
        }

        if (end < last - 1) {
            container.append(ellipsisTemplate.content.cloneNode(true));
        }

        if (last > 0) {
            container.append(createBtn(last, total, current === last));
        }

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

        const lang = window.langDatatable?.[tableId] ?? {};
        const template = lang.showing ?? "Showing :from - :to of :total";

        infoContainer.text(
            template
                .replace(":from", info.start + 1)
                .replace(":to", info.end)
                .replace(":total", info.recordsTotal),
        );

        initLucide();
    };

    datatable.on("draw", renderPagination);

    return datatable;
}

