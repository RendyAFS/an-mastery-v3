import { initLucide } from "./lucide";

export function initUi() {
    // Re-init Preline
    window.HSStaticMethods?.autoInit();

    // Re-init Lucide
    initLucide();

    // Re-init Rupiah Input jika ada
    window.RupiahInput?.init?.();
}

// jadikan global
window.initUi = initUi;
