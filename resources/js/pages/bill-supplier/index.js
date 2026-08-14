import initCardgrid from "@/utils/cardgrid";
import RupiahInput from "@/utils/rupiah-input";
import ApiProvider from "@/utils/api-provider";
import filterStorage from "@/utils/filter-storage";
import { currentIsoWeek, nextIsoWeek } from "@/utils/week";

const STORAGE_KEY = "bill-supplier-index-filters";

const PageScript = (function () {
    const formatCurrency = (value) => `Rp${RupiahInput.format(value ?? 0)}`;
    let activeSupplierId = null;
    let palette = [];
    let cardgrid;

    const applyFiltersFromUrl = () => {
        const params = filterStorage.loadFilterParams();

        $("#filter-week-start").val(
            params.get("week_start") || currentIsoWeek(),
        );
        $("#filter-week-end").val(params.get("week_end") || nextIsoWeek());
    };

    const syncUrl = () => {
        const params = new URLSearchParams();
        params.set("week_start", $("#filter-week-start").val());
        params.set("week_end", $("#filter-week-end").val());

        filterStorage.saveFilterParams(params);
    };

    const setDefaultWeekFilters = () => {
        $("#filter-week-start").val(currentIsoWeek());
        $("#filter-week-end").val(nextIsoWeek());
        syncUrl();
    };

    const patternSvg = (pattern) => {
        if (pattern === "dots") {
            return `
            <svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" width="14" height="14" patternUnits="userSpaceOnUse">
                        <circle cx="3" cy="3" r="2" fill="white" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)" />
            </svg>`;
        }
        if (pattern === "waves") {
            return `
            <svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="waves" width="20" height="10" patternUnits="userSpaceOnUse">
                        <path d="M0 5 Q5 0 10 5 T20 5" stroke="white" stroke-width="1.5" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#waves)" />
            </svg>`;
        }
        if (pattern === "none") return "";
        return `
        <svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="stripes" width="16" height="16" patternTransform="rotate(45)" patternUnits="userSpaceOnUse">
                    <line x1="0" y1="0" x2="0" y2="16" stroke="white" stroke-width="6" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#stripes)" />
        </svg>`;
    };

    const coverBackground = (pattern) => `
        <div class="absolute -right-6 -bottom-8 size-28 rounded-full border-4 border-white/20"></div>
        <div class="absolute -right-2 -bottom-14 size-28 rounded-full border-4 border-white/10"></div>
        ${patternSvg(pattern)}
    `;

    const renderCard = (item) => {
        const isDeleted = item.deleted_at !== null;
        const hasUnpaid = item.unpaid_bills_count > 0;
        const style = item.cover_style ?? {
            color_from: "#6366f1",
            color_to: "#4338ca",
            icon: "book-marked",
            pattern: "stripes",
        };

        const statusBadge = isDeleted
            ? `<span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white/20 backdrop-blur text-white flex items-center gap-1 shrink-0 border border-white/30">
                 <i data-lucide="trash-2" class="size-3.5"></i> ${window.langBillSupplier.card.deleted}
               </span>`
            : !item.is_active
              ? `<span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white/20 backdrop-blur text-white shrink-0 border border-white/30">${window.langBillSupplier.card.inactive}</span>`
              : `<span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white/20 backdrop-blur text-white flex items-center gap-1 shrink-0 border border-white/30">
                   <i data-lucide="badge-check" class="size-3.5"></i> ${window.langBillSupplier.card.active}
                 </span>`;

        return `
        <div class="h-full bg-(--color-light) dark:bg-(--color-dark) rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 overflow-hidden flex flex-col
            ${isDeleted ? "opacity-70" : ""}
            border border-(--color-gray)/10" data-card-id="${item.id}">

            <div class="relative px-4 pt-4 pb-6 overflow-hidden cursor-pointer card-clickable"
                 style="background: linear-gradient(135deg, ${style.color_from}, ${style.color_to});">
                ${coverBackground(style.pattern)}

                <div class="relative flex items-start justify-between gap-2">
                    <div class="p-2 rounded-lg bg-white/15 backdrop-blur border border-white/20">
                        <i data-lucide="${style.icon}" class="size-5 text-white"></i>
                    </div>
                    <div class="flex items-center gap-1.5">
                        ${statusBadge}
                        <button type="button" data-edit-style="${item.id}"
                            class="p-1.5 rounded-full bg-white/15 backdrop-blur border border-white/20 hover:bg-white/25 cursor-pointer">
                            <i data-lucide="palette" class="size-3.5 text-white"></i>
                        </button>
                    </div>
                </div>

                <div class="relative mt-5">
                    <p class="font-bold text-lg text-white leading-snug line-clamp-2">${item.name}</p>
                    ${
                        item.contact
                            ? `<p class="text-xs text-(--color-light) mt-1 flex items-center gap-1"><i data-lucide="phone" class="size-3.5"></i> ${item.contact}</p>`
                            : `<p class="text-sm text-(--color-light) dark:text-(--color-light-gray) italic line-clamp-2 flex items-start gap-1.5">
                            <i data-lucide="phone-off" class="size-3.5 mt-0.5 shrink-0"></i>
                            <span>${window.langBillSupplier.card.no_contact}</span>
                        </p>`
                    }
                </div>
            </div>

            <div class="h-1.5 bg-(--color-light) dark:bg-(--color-dark) relative">
                <div class="absolute inset-x-4 top-0 h-px bg-(--color-dark)/20"></div>
            </div>

            <div class="p-4 flex flex-col gap-3 flex-1 cursor-pointer card-clickable">
                ${
                    item.address
                        ? `
                            <p class="text-sm text-(--color-dark-gray) dark:text-(--color-light-gray) line-clamp-2 flex items-start gap-1.5">
                                <i data-lucide="map-pin" class="size-3.5 mt-0.5 shrink-0"></i>
                                <span>${item.address}</span>
                            </p>`
                        : `<p class="text-sm text-(--color-red) italic line-clamp-2 flex items-start gap-1.5">
                            <i data-lucide="map-pin-off" class="size-3.5 mt-0.5 shrink-0"></i>
                            <span>${window.langBillSupplier.card.no_address}</span>
                        </p>`
                }

                <div class="grid grid-cols-2 gap-2 mt-1">
                    <div class="rounded-lg bg-(--color-dark)/8 dark:bg-white/5 p-3 space-y-1">
                        <p class="text-xs text-(--color-dark) dark:text-(--color-light-gray) flex items-center gap-1">
                            <i data-lucide="package" class="size-3.5"></i> ${window.langBillSupplier.card.unbilled}
                        </p>
                        <p class="text-lg font-bold">${item.unbilled_sablons_count}</p>
                    </div>
                    <div class="rounded-lg bg-(--color-dark)/8 dark:bg-white/5 p-3 space-y-1">
                        <p class="text-xs text-(--color-dark) dark:text-(--color-light-gray) flex items-center gap-1">
                            <i data-lucide="receipt" class="size-3.5"></i> ${window.langBillSupplier.card.unpaid}
                        </p>
                        <p class="text-lg font-bold ${hasUnpaid ? "text-(--color-red)" : "text-(--color-gray)"}">
                            ${item.unpaid_bills_count}
                        </p>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-t border-dashed border-(--color-gray)/25 flex items-center justify-between">
                    <span class="text-sm font-semibold flex items-center gap-1">
                        <i data-lucide="wallet" class="size-4"></i> ${window.langBillSupplier.card.total_bill}
                    </span>
                    <span class="font-extrabold text-base ${hasUnpaid ? "text-(--color-primary)" : "text-(--color-gray)"}">
                        ${formatCurrency(item.total_unpaid)}
                    </span>
                </div>
            </div>
        </div>`;
    };

    const updatePreview = () => {
        const colorFrom = $("#cs-color-from").val();
        const colorTo = $("#cs-color-to").val();
        const icon = $("#cs-icon").val() || "book-marked";
        const pattern = $("#cs-pattern").val() || "stripes";

        $("#cover-style-preview").css(
            "background",
            `linear-gradient(135deg, ${colorFrom}, ${colorTo})`,
        ).html(`
                ${coverBackground(pattern)}
                <div class="relative p-2 rounded-lg bg-white/15 backdrop-blur border border-white/20 inline-flex">
                    <i data-lucide="${icon}" class="size-5 text-white"></i>
                </div>`);

        if (window.lucide) window.lucide.createIcons();
    };

    const renderPalette = () => {
        $("#cover-style-palette").html(
            palette
                .map(
                    (c) => `
                <button type="button" data-palette-from="${c.from}" data-palette-to="${c.to}"
                    class="size-8 rounded-full border-2 border-(--color-gray)/20 cursor-pointer"
                    style="background: linear-gradient(135deg, ${c.from}, ${c.to});"></button>`,
                )
                .join(""),
        );
    };

    const setSelectValue = (id, value) => {
        const el = document.getElementById(id);
        if (!el) return;

        el.value = value;

        if (window.HSSelect) {
            const instance = window.HSSelect.getInstance(el);
            instance?.setValue(value);
        }
    };

    const openModal = async (supplierId) => {
        activeSupplierId = supplierId;
        const response = await ApiProvider.get(
            route("suppliers.cover-style.edit", supplierId),
        );

        palette = response.presets.palette;
        renderPalette();

        $("#cs-color-from").val(response.style.color_from);
        $("#cs-color-to").val(response.style.color_to);

        setSelectValue("cs-icon", response.style.icon);
        setSelectValue("cs-pattern", response.style.pattern);

        updatePreview();
        $("#cover-style-modal").removeClass("hidden");
        if (window.lucide) window.lucide.createIcons();
    };

    const closeModal = () => {
        $("#cover-style-modal").addClass("hidden");
        activeSupplierId = null;
    };

    const bindEvents = () => {
        $(document).on("click", "[data-edit-style]", function (e) {
            e.stopPropagation();
            openModal($(this).data("edit-style"));
        });

        $(document).on("click", "[data-modal-close]", closeModal);

        $(document).on(
            "input change",
            "#cs-color-from, #cs-color-to",
            updatePreview,
        );
        $(document).on("change", "#cs-icon, #cs-pattern", updatePreview);

        $(document).on("click", "[data-palette-from]", function () {
            $("#cs-color-from").val($(this).data("palette-from"));
            $("#cs-color-to").val($(this).data("palette-to"));
            updatePreview();
        });

        $(document).on("click", "#cs-save", async function () {
            if (!activeSupplierId) return;

            await ApiProvider.put(
                route("suppliers.cover-style.update", activeSupplierId),
                {
                    color_from: $("#cs-color-from").val(),
                    color_to: $("#cs-color-to").val(),
                    icon: $("#cs-icon").val(),
                    pattern: $("#cs-pattern").val(),
                },
            );

            Toast.success(
                window.langCustomAlert.success,
                window.langBillSupplier.cover_style.saved_success,
            );
            closeModal();
            cardgrid?.reload();
        });

        $(document).on(
            "change",
            "#filter-week-start, #filter-week-end",
            function () {
                syncUrl();
                cardgrid?.reload();
            },
        );

        $(document).on("click", "#filter-week-reset", function () {
            setDefaultWeekFilters();
            cardgrid?.reload();
        });
    };

    return {
        init() {
            applyFiltersFromUrl();
            syncUrl();
            bindEvents();

            cardgrid = initCardgrid({
                containerId: "#bill-supplier-cardgrid",
                ajax: {
                    url: route("bill_suppliers.index"),
                    data: function () {
                        return {
                            week_start: $("#filter-week-start").val(),
                            week_end: $("#filter-week-end").val(),
                        };
                    },
                },
                renderCard,
                pageLength: 12,
                cardClickRoute: (row) => {
                    const params = new URLSearchParams({
                        week_start: $("#filter-week-start").val(),
                        week_end: $("#filter-week-end").val(),
                    });

                    return `${route("bill_suppliers.by-supplier", row.id)}?${params.toString()}`;
                },
            });

            if (window.lucide) window.lucide.createIcons();
        },
    };
})();

$(function () {
    PageScript.init();
});
