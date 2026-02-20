import "./bootstrap";
import "preline";
// utils
import "./utils/toggle-dark-mode";
// import "./utils/custom-toast";
import "./utils/custom-alert";
import "./utils/sidebar-state";
import "./utils/custom-select";

import { initLucide } from "./utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    if (window.HSStaticMethods) {
        window.HSStaticMethods.autoInit();
    }

    initLucide();
});

console.log('app.js loaded');
