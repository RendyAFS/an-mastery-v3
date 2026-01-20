document.addEventListener("DOMContentLoaded", () => {
    /**
     * Handle submit loading button
     */
    document.querySelectorAll("form").forEach((form) => {
        form.addEventListener("submit", () => {
            const submitBtn = form.querySelector(
                'button[type="submit"][data-button-loading]',
            );

            if (!submitBtn) return;

            setLoading(submitBtn, true);
        });
    });

    /**
     * Reset loading state on back / validation error
     */
    document.querySelectorAll("[data-button-loading]").forEach((btn) => {
        setLoading(btn, false);
    });
});

/**
 * Toggle loading state
 */
function setLoading(button, isLoading) {
    const spinner = button.querySelector("[data-spinner]");
    const text = button.querySelector("[data-text]");
    const icon = button.querySelector("[data-icon]");
    const loadingText = button.dataset.loadingText;

    if (isLoading) {
        button.disabled = true;
        spinner?.classList.remove("hidden");
        icon?.classList.add("hidden");
        if (text && loadingText) text.textContent = loadingText;
    } else {
        button.disabled = false;
        spinner?.classList.add("hidden");
        icon?.classList.remove("hidden");
    }
}

export default {};
