import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";
import { glob } from "glob";
import { VitePWA } from "vite-plugin-pwa";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                ...glob.sync([
                    "resources/js/utils/**/*.js",
                    "resources/js/pages/**/*.js",
                ]),
            ],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: "autoUpdate",
            outDir: "public",
            buildBase: "/",
            scope: "/",
            manifest: {
                name: "AN Mastery",
                short_name: "AN Mastery",
                description: "Aplikasi Management Sistem AN Mastery",
                theme_color: "#4f46e5",
                background_color: "#ffffff",
                display: "standalone",
                orientation: "portrait",
                start_url: "/",
                scope: "/",
                icons: [
                    {
                        src: "/assets/pwa-192x192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "/assets/pwa-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                    {
                        src: "/assets/pwa-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                        purpose: "any maskable",
                    },
                ],
            },
            devOptions: {
                enabled: true,
            },
            workbox: {
                globIgnores: ["vendor/**"],
                maximumFileSizeToCacheInBytes: 5 * 1024 * 1024,
            },
        }),
    ],
    server: {
        host: "127.0.0.1",
        port: 5173,
        headers: {
            "Access-Control-Allow-Origin": "*",
        },
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
