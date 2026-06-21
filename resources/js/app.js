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

import { initLucide } from "./utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    if (window.HSStaticMethods) {
        window.HSStaticMethods.autoInit();
    }

    initLucide();
});
