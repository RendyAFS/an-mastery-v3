document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("#hs-sidebar-content-push");
    const body = document.body;

    if (!sidebar) return;

    const state = localStorage.getItem("sidebar:state");

    if (state === "close" && window.innerWidth >= 1024) {
        body.classList.add("hs-overlay-minified");
    }

    if (state === "open" && window.innerWidth < 1024) {
        const openBtn = document.querySelector(
            '[data-hs-overlay="#hs-sidebar-content-push"]'
        );
        openBtn?.click();
    }

    const observer = new MutationObserver(() => {
        const isMinified = body.classList.contains("hs-overlay-minified");

        localStorage.setItem(
            "sidebar:state",
            isMinified ? "close" : "open"
        );
    });

    observer.observe(body, {
        attributes: true,
        attributeFilter: ["class"],
    });

    sidebar.addEventListener("open.hs.overlay", () => {
        localStorage.setItem("sidebar:state", "open");
    });

    sidebar.addEventListener("close.hs.overlay", () => {
        localStorage.setItem("sidebar:state", "close");
    });
});
