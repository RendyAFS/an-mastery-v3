import "./utils/suppress-hsdatatable-warning";
import "./bootstrap";
import "preline";
import * as FloatingUIDOM from "@floating-ui/dom";
import fixTextareaEnter from "./utils/fix-textarea-enter";
window.FloatingUIDOM = FloatingUIDOM;

// utils
import "./utils/sidebar-mode";
import "./utils/toggle-dark-mode";
import "./utils/custom-alert";
import "./utils/sidebar-state";
import "./utils/custom-select";
import "./utils/number-input";

import disableNumberScroll from "./utils/disable-number-scroll";
import initUi from "./utils/ui-init";

document.addEventListener("DOMContentLoaded", () => {
    initUi();
    disableNumberScroll();
    fixTextareaEnter();
    window.Alpine.start();
});
