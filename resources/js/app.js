import "./bootstrap";
import "preline";
import "./utils/toggle-dark-mode";

import Alpine from "alpinejs";

// lucide icons
lucide.createIcons();

// alpinejs
window.Alpine = Alpine;
Alpine.start();
