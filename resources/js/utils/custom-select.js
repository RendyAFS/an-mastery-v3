document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-clear-select]");
    if (!btn) return;

    const selectId = btn.getAttribute("data-clear-select");
    const select = document.getElementById(selectId);
    if (!select) return;

    select.value = "";

    select.dispatchEvent(new Event("change", { bubbles: true }));

    if (window.HSSelect) {
        const instance = HSSelect.getInstance(select);
        instance?.setValue("");
    }
});
