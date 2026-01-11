import "./bootstrap";
import "preline";
import "./utils/toggle-dark-mode";
import customToast from "./utils/toast";
import Alpine from "alpinejs";
// lucide icons
lucide.createIcons();
// alpinejs
window.Alpine = Alpine;
Alpine.data("customToast", customToast);
Alpine.start();
