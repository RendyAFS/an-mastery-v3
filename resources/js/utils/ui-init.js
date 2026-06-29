import { initLucide } from "./lucide";
import RupiahInput from "./rupiah-input";

export default function initUi() {
    requestAnimationFrame(() => {
        window.HSStaticMethods?.autoInit();
        initLucide();
        RupiahInput.init();
    });
}
