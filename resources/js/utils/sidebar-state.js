document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("#hs-sidebar-content-push");

    if (!sidebar) return;

    const minifyButton = document.querySelector(
        '[data-hs-overlay-minifier="#hs-sidebar-content-push"]',
    );

    const state = localStorage.getItem("sidebar:state");

    if (state === "close" && window.innerWidth >= 1024) {
        requestAnimationFrame(() => {
            if (!document.body.classList.contains("hs-overlay-minified")) {
                minifyButton?.click();
            }
        });
    }

    const observer = new MutationObserver(() => {
        const isMinified = document.body.classList.contains(
            "hs-overlay-minified",
        );

        localStorage.setItem("sidebar:state", isMinified ? "close" : "open");
    });

    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ["class"],
    });
});
