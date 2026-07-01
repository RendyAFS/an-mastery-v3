import "./bootstrap";
import "preline";
import * as FloatingUIDOM from "@floating-ui/dom";
window.FloatingUIDOM = FloatingUIDOM;

// utils
import "./utils/toggle-dark-mode";
import "./utils/custom-alert";
import "./utils/sidebar-state";
import "./utils/custom-select";

import disableNumberScroll from "./utils/disable-number-scroll";
import initUi from "./utils/ui-init";

document.addEventListener("DOMContentLoaded", () => {
    initUi();
    disableNumberScroll();
});
