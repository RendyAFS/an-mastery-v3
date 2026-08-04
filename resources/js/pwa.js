import { registerSW } from "virtual:pwa-register";

if ("serviceWorker" in navigator) {
    registerSW({
        immediate: true,
        onNeedRefresh() {
            console.log("Konten PWA baru tersedia. Memperbarui...");
        },
        onOfflineReady() {
            console.log("Aplikasi AN Mastery V3 siap digunakan secara offline.");
        },
    });
}
