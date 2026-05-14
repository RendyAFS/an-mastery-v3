import { initLucide } from "./lucide";

document.addEventListener("hsSelect.afterInit", (e) => {
    initLucide(e.target);
});

const toggleClearButton = (select) => {
    const btn = document.querySelector(`[data-clear-select="${select.id}"]`);

    if (!btn) return;

    btn.style.display =
        select.value && select.value !== "" ? "inline-flex" : "none";
};

export const setSelectOption = (selector, item) => {
    if (!item) return;

    const select =
        typeof selector === "string"
            ? document.querySelector(selector)
            : selector;

    if (!select) return;

    const hsSelect = window.HSSelect?.getInstance(select);

    if (!hsSelect) return;

    let option = select.querySelector(`option[value="${item.id}"]`);

    if (!option) {
        option = document.createElement("option");

        option.value = item.id;
        option.text = item.name;

        select.appendChild(option);
    }

    hsSelect.setValue(String(item.id));

    select.dispatchEvent(new Event("change", { bubbles: true }));

    toggleClearButton(select);
};

export const clearSelect = (selector) => {
    const select =
        typeof selector === "string"
            ? document.querySelector(selector)
            : selector;

    if (!select) return;

    select.value = "";

    const hsSelect = window.HSSelect?.getInstance(select);

    hsSelect?.setValue("");

    select.dispatchEvent(new Event("change", { bubbles: true }));

    toggleClearButton(select);
};

export const resetAllSelects = (container = document) => {
    container.querySelectorAll("select[data-hs-select]").forEach((select) => {
        clearSelect(select);
    });
};

document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("select[data-hs-select]").forEach((select) => {
        toggleClearButton(select);

        select.addEventListener("change", () => {
            toggleClearButton(select);
        });
    });

    document.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-clear-select]");

        if (!btn) return;

        const selectId = btn.getAttribute("data-clear-select");

        const select = document.getElementById(selectId);

        if (!select) return;

        clearSelect(select);
    });
});
