import "./bootstrap";
import "preline";

// floating ui
import * as FloatingUIDOM from "@floating-ui/dom";
window.FloatingUIDOM = FloatingUIDOM;

// utils
import "./utils/toggle-dark-mode";
import "./utils/custom-alert";
import "./utils/sidebar-state";
import "./utils/custom-select";
import "./utils/rupiah-input";
import "./utils/ui-init";

// re-init preline after DOM loaded
document.addEventListener("DOMContentLoaded", () => {
    window.initUi();
});

// remove scroll number input
document.addEventListener(
    "wheel",
    (e) => {
        if (
            document.activeElement &&
            document.activeElement.type === "number"
        ) {
            document.activeElement.blur();
        }
    },
    { passive: true },
);
