export default function initFormEnterSubmit() {
    document.addEventListener(
        "keydown",
        (e) => {
            if (e.key !== "Enter") return;

            const target = e.target;

            // 1. Skip TEXTAREA (allow new lines)
            if (target && target.tagName === "TEXTAREA") {
                e.stopImmediatePropagation();
                return;
            }

            // 2. Skip BUTTON or A elements (unless it's an explicit submit button)
            if (target && (target.tagName === "BUTTON" || target.tagName === "A")) {
                if (
                    target.type !== "submit" &&
                    !target.hasAttribute("data-button-loading")
                ) {
                    return;
                }
            }

            // 3. Skip if focus is inside an open HSSelect dropdown menu
            if (
                target &&
                (target.closest(".hs-select-dropdown") ||
                    target.closest("[data-hs-select-dropdown]"))
            ) {
                return;
            }

            // 4. Skip elements marked with data-no-enter-submit
            if (target && target.closest("[data-no-enter-submit]")) return;

            // 5. Find active form (from target or from active open modal)
            let form = target ? target.closest("form") : null;

            if (!form) {
                const openModal = document.querySelector(
                    ".hs-overlay:not(.hidden), [role='dialog']:not(.hidden)",
                );
                if (openModal) {
                    form =
                        openModal.closest("form") || openModal.querySelector("form");
                }
            }

            if (!form) return;

            // 6. Find submit button inside the active form
            const submitBtn = form.querySelector(
                'button[type="submit"], input[type="submit"]',
            );

            if (submitBtn && submitBtn.disabled) return;

            e.preventDefault();

            if (typeof form.requestSubmit === "function") {
                try {
                    form.requestSubmit(submitBtn || undefined);
                } catch (err) {
                    if (submitBtn) submitBtn.click();
                }
            } else if (submitBtn) {
                submitBtn.click();
            }
        },
        true,
    );
}
