import { initLucide } from "@/utils/lucide";

export default function initCardgrid({
    containerId,
    filterSelector = null,
    ajax,
    renderCard,
    pageLength = 12,
}) {
    const container = document.querySelector(containerId);
    if (!container) return null;

    const gridId = containerId.replace("#", "");

    let state = {
        page: 1,
        perPage: pageLength,
        search: "",
        filter: null,
        total: 0,
        lastPage: 1,
    };

    let searchTimeout = null;

    // ── Fetch ─────────────────────────────────────────────────────────────────
    const fetchData = async () => {
        setLoading(true);

        const params = new URLSearchParams({
            page: state.page,
            per_page: state.perPage,
            search: state.search,
        });

        if (state.filter) params.append("filter", state.filter);

        // Allow caller to append extra params
        if (ajax.data) {
            const extra = ajax.data();
            Object.entries(extra).forEach(([k, v]) => params.append(k, v));
        }

        try {
            const res = await fetch(`${ajax.url}?${params.toString()}`, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });
            const json = await res.json();

            state.total = json.meta?.total ?? json.total ?? 0;
            state.lastPage = json.meta?.last_page ?? json.last_page ?? 1;

            render(json.data ?? []);
            renderPagination();
            renderInfo();
        } catch (e) {
            console.error("CardGrid fetch error:", e);
        } finally {
            setLoading(false);
        }
    };

    // ── Render cards ──────────────────────────────────────────────────────────
    const render = (items) => {
        container.innerHTML = "";

        const emptyEl = document.getElementById(`${gridId}-empty`);
        if (!items.length) {
            emptyEl?.classList.remove("hidden");
            container.classList.add("hidden");
            return;
        }

        emptyEl?.classList.add("hidden");
        container.classList.remove("hidden");
        container.innerHTML = items.map(renderCard).join("");

        initLucide();
        if (window.HSStaticMethods) window.HSStaticMethods.autoInit();
    };

    // ── Loading state ─────────────────────────────────────────────────────────
    const setLoading = (loading) => {
        const loadingEl = document.getElementById(`${gridId}-loading`);
        if (loading) {
            loadingEl?.classList.remove("hidden");
            container.classList.add("hidden");
        } else {
            loadingEl?.classList.add("hidden");
        }
    };

    // ── Info text ─────────────────────────────────────────────────────────────
    const renderInfo = () => {
        const from = (state.page - 1) * state.perPage + 1;
        const to = Math.min(state.page * state.perPage, state.total);
        const infoEl = document.getElementById("cg-info");
        if (infoEl) {
            infoEl.textContent = state.total
                ? `Showing ${from} – ${to} of ${state.total}`
                : "No results";
        }
    };

    // ── Pagination ────────────────────────────────────────────────────────────
    const renderPagination = () => {
        const paginationEl = document.getElementById("cg-pagination");
        if (!paginationEl) return;
        paginationEl.innerHTML = "";

        const current = state.page - 1; // 0-indexed for compatibility
        const total = state.lastPage;
        const last = total - 1;

        const btnTpl = document.getElementById("cg-pagination-btn-template");
        const ellipsisTpl = document.getElementById(
            "cg-pagination-ellipsis-template",
        );
        const prevTpl = document.getElementById("cg-pagination-prev-template");
        const nextTpl = document.getElementById("cg-pagination-next-template");

        const createBtn = (page, label = null, isCurrent = false) => {
            const btn = btnTpl.content.cloneNode(true).querySelector("button");
            btn.dataset.page = page + 1; // back to 1-indexed
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

        // Prev
        const prevBtn = prevTpl.content.cloneNode(true).querySelector("button");
        prevBtn.dataset.page = state.page - 1;
        if (current === 0) {
            prevBtn.classList.add("opacity-50", "cursor-not-allowed");
            prevBtn.disabled = true;
        } else prevBtn.classList.add("hover:bg-(--color-light-gray)");
        paginationEl.append(prevBtn);

        // First page
        paginationEl.append(createBtn(0, null, current === 0));

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

        if (start > 1) paginationEl.append(ellipsisTpl.content.cloneNode(true));
        for (let i = start; i <= end; i++) {
            if (i > 0 && i < last)
                paginationEl.append(createBtn(i, null, i === current));
        }
        if (end < last - 1)
            paginationEl.append(ellipsisTpl.content.cloneNode(true));
        if (last > 0)
            paginationEl.append(createBtn(last, total, current === last));

        // Next
        const nextBtn = nextTpl.content.cloneNode(true).querySelector("button");
        nextBtn.dataset.page = state.page + 1;
        if (current === last) {
            nextBtn.classList.add("opacity-50", "cursor-not-allowed");
            nextBtn.disabled = true;
        } else nextBtn.classList.add("hover:bg-(--color-light-gray)");
        paginationEl.append(nextBtn);

        initLucide();
    };

    // ── Event Listeners ───────────────────────────────────────────────────────
    document
        .getElementById("cg-search")
        ?.addEventListener("input", function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                state.search = this.value;
                state.page = 1;
                fetchData();
            }, 400);
        });

    document
        .getElementById("cg-length")
        ?.addEventListener("change", function () {
            state.perPage = parseInt(this.value);
            state.page = 1;
            fetchData();
        });

    if (filterSelector) {
        document.addEventListener("change", (e) => {
            if (e.target.matches(filterSelector)) {
                state.filter = e.target.value;
                state.page = 1;
                fetchData();
            }
        });
    }

    document.addEventListener("click", (e) => {
        const btn = e.target.closest("#cg-pagination button[data-page]");
        if (!btn) return;
        const page = parseInt(btn.dataset.page);
        if (!isNaN(page) && page >= 1 && page <= state.lastPage) {
            state.page = page;
            fetchData();
        }
    });

    // ── Public API ────────────────────────────────────────────────────────────
    const reload = () => fetchData();

    // Initial load
    fetchData();

    return { reload };
}
