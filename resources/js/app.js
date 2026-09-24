import "./utils/suppress-hsdatatable-warning";
import "./bootstrap";
import "preline";
import * as FloatingUIDOM from "@floating-ui/dom";
import initFormEnterSubmit from "./utils/form-enter-submit";
window.FloatingUIDOM = FloatingUIDOM;

// utils
import "./utils/sidebar-mode";
import "./utils/toggle-dark-mode";
import "./utils/custom-alert";
import "./utils/custom-select";
import "./utils/button-group";
import "./utils/number-input";
import "./utils/flatpickr-init";

import disableNumberScroll from "./utils/disable-number-scroll";
import initUi from "./utils/ui-init";
import initSplashScreen from "./utils/splash-screen";
import ApiProvider from "./utils/api-provider";

initSplashScreen();

document.addEventListener("DOMContentLoaded", () => {
    initUi();
    disableNumberScroll();
    initFormEnterSubmit();
    window.Alpine.start();

    // Version Update Banner — Acknowledge button
    const btnAcknowledge = document.getElementById("btn-acknowledge-version");
    if (btnAcknowledge) {
        btnAcknowledge.addEventListener("click", async () => {
            try {
                await ApiProvider.put(route("version.acknowledge"));

                const banner = document.getElementById("version-update-banner");
                if (banner) {
                    banner.style.transition = "opacity 0.4s ease, max-height 0.4s ease";
                    banner.style.opacity = "0";
                    banner.style.maxHeight = "0";
                    banner.style.overflow = "hidden";
                    banner.style.padding = "0";
                    setTimeout(() => banner.remove(), 450);
                }

                Toast.success(
                    window.langCustomAlert?.success ?? "Success",
                    window.langCustomAlert?.version_acknowledged ?? "Version acknowledged successfully.",
                );
            } catch (error) {
                console.error("Acknowledge version error:", error);
            }
        });
    }
});
