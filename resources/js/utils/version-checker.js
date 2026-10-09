/**
 * App Version Checker & Update Notification
 * Integrates with Firebase Realtime Database via live Server-Sent Events (SSE) stream
 * for INSTANT real-time updates without page reload, plus automated deployment terminal execution.
 */

(function () {
    "use strict";

    // Firebase RTDB URL configured for this app
    const FIREBASE_DB_URL = "https://an-mastery-v3-default-rtdb.asia-southeast1.firebasedatabase.app";
    let activeEventSource = null;
    let cachedLocalVersion = null;

    /**
     * Parse semantic version string (e.g. "1.2.0" or "v1.2.0-beta")
     */
    function parseSemVer(v) {
        if (!v) return [0, 0, 0];
        const clean = v.toString().replace(/^v/i, "").trim().split("-")[0];
        return clean.split(".").map((n) => parseInt(n, 10) || 0);
    }

    /**
     * Compare versions: returns -1 if v1 < v2 (update available), 0 if equal, 1 if v1 > v2
     */
    function compareVersions(v1, v2) {
        const [maj1, min1, pat1] = parseSemVer(v1);
        const [maj2, min2, pat2] = parseSemVer(v2);

        if (maj1 !== maj2) return maj1 < maj2 ? -1 : 1;
        if (min1 !== min2) return min1 < min2 ? -1 : 1;
        if (pat1 !== pat2) return pat1 < pat2 ? -1 : 1;
        return 0;
    }

    /**
     * Get local version from meta tag or window
     */
    function getLocalVersion() {
        if (cachedLocalVersion) return cachedLocalVersion;
        const metaTag = document.querySelector('meta[name="app-version"]');
        cachedLocalVersion = metaTag ? metaTag.content : (window.appVersion || "1.0.0");
        return cachedLocalVersion;
    }

    /**
     * Display the navbar update pill and update modal content
     */
    function showUpdateNotification(localVersion, firebaseData) {
        const container = document.getElementById("navbar-version-checker-container");
        const updateText = document.getElementById("navbar-update-text");

        if (container) {
            container.classList.remove("hidden");
            container.classList.add("flex");
        }

        const isEn = document.documentElement.lang === "en";
        const newVersion = firebaseData.version || (isEn ? "Latest" : "Terbaru");
        if (updateText) {
            updateText.innerText = `Update: v${newVersion}`;
        }

        // Populate Modal Fields
        const modalCurr = document.getElementById("modal-curr-version");
        const modalNew = document.getElementById("modal-new-version");
        const modalSubtitle = document.getElementById("modal-update-subtitle");
        const modalReleaseContainer = document.getElementById("modal-release-info-container");
        const modalReleaseName = document.getElementById("modal-release-name");
        const modalPublishedMeta = document.getElementById("modal-published-meta");
        const modalDescContainer = document.getElementById("modal-description-container");
        const modalDescContent = document.getElementById("modal-description-content");
        const modalChangelogContainer = document.getElementById("modal-changelog-container");
        const modalChangelogContent = document.getElementById("modal-changelog-content");

        if (modalCurr) modalCurr.innerText = `v${localVersion}`;
        if (modalNew) modalNew.innerText = `v${newVersion}`;

        if (modalSubtitle) {
            modalSubtitle.innerText = isEn
                ? `A new version v${newVersion} has been released (your active version: v${localVersion})`
                : `Versi baru v${newVersion} telah dirilis (versi aktif Anda: v${localVersion})`;
        }

        if (modalReleaseContainer && (firebaseData.release_name || firebaseData.published_at)) {
            modalReleaseContainer.classList.remove("hidden");
            if (modalReleaseName) {
                modalReleaseName.innerText = firebaseData.release_name || (isEn ? `Release v${newVersion}` : `Rilis v${newVersion}`);
            }
            if (modalPublishedMeta && firebaseData.published_at) {
                const date = new Date(firebaseData.published_at).toLocaleString();
                const by = firebaseData.published_by ? (isEn ? ` by ${firebaseData.published_by}` : ` oleh ${firebaseData.published_by}`) : "";
                modalPublishedMeta.innerHTML = `<i data-lucide="clock" class="size-3 text-(--color-primary)"></i> <span>${isEn ? `Published: ${date}${by}` : `Dipublikasikan: ${date}${by}`}</span>`;
            }
        }

        // Description Box
        if (modalDescContainer && firebaseData.description && firebaseData.description.trim()) {
            modalDescContainer.classList.remove("hidden");
            if (modalDescContent) {
                modalDescContent.innerText = firebaseData.description;
            }
        } else if (modalDescContainer) {
            modalDescContainer.classList.add("hidden");
        }

        // Changelog Box
        if (modalChangelogContainer && firebaseData.changelog && firebaseData.changelog.trim()) {
            modalChangelogContainer.classList.remove("hidden");
            if (modalChangelogContent) {
                modalChangelogContent.innerText = firebaseData.changelog;
            }
        } else if (modalChangelogContainer) {
            modalChangelogContainer.classList.add("hidden");
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    /**
     * Hide update notification
     */
    function hideUpdateNotification() {
        const container = document.getElementById("navbar-version-checker-container");
        if (container) {
            container.classList.add("hidden");
            container.classList.remove("flex");
        }
    }

    let currentFirebaseData = {};

    /**
     * Process received Firebase Version payload and update UI
     */
    function processVersionData(fbData) {
        if (!fbData || typeof fbData !== "object" || !fbData.version) {
            hideUpdateNotification();
            window.dispatchEvent(new CustomEvent("app-version:firebase-updated", {
                detail: { fbData: null, comparison: null, localVersion: getLocalVersion() }
            }));
            return;
        }

        currentFirebaseData = { ...currentFirebaseData, ...fbData };
        const localVersion = getLocalVersion();
        const comparison = compareVersions(localVersion, currentFirebaseData.version);

        if (comparison === -1) {
            showUpdateNotification(localVersion, currentFirebaseData);
        } else {
            hideUpdateNotification();
        }

        // Broadcast event for all pages/components to react in real-time
        window.dispatchEvent(new CustomEvent("app-version:firebase-updated", {
            detail: { fbData: currentFirebaseData, comparison, localVersion }
        }));
    }

    /**
     * Listen to Firebase Realtime Database SSE Stream
     */
    function initFirebaseRealtimeListener() {
        try {
            const url = `${FIREBASE_DB_URL.replace(/\/+$/, "")}/app_version.json`;
            if (activeEventSource) {
                activeEventSource.close();
            }

            activeEventSource = new EventSource(url);

            // Firebase RTDB emits 'put' and 'patch' events for data changes
            activeEventSource.addEventListener("put", (e) => {
                try {
                    const parsed = JSON.parse(e.data);
                    if (parsed.path === "/" || parsed.path === "") {
                        if (parsed.data) {
                            currentFirebaseData = typeof parsed.data === "object" ? parsed.data : {};
                            processVersionData(currentFirebaseData);
                        } else {
                            processVersionData(null);
                        }
                    } else if (parsed.path) {
                        // Sub-path edited directly (e.g. path "/version" or "/changelog")
                        const key = parsed.path.replace(/^\//, "").split("/")[0];
                        currentFirebaseData[key] = parsed.data;
                        processVersionData(currentFirebaseData);
                    }
                } catch (err) {
                    console.debug("Firebase parse error:", err);
                }
            });

            activeEventSource.addEventListener("patch", (e) => {
                try {
                    const parsed = JSON.parse(e.data);
                    if (parsed.data && typeof parsed.data === "object") {
                        currentFirebaseData = { ...currentFirebaseData, ...parsed.data };
                        processVersionData(currentFirebaseData);
                    }
                } catch (err) {
                    console.debug("Firebase patch parse error:", err);
                }
            });

            activeEventSource.onerror = function () {
                // Silently fallback; browser will automatically reconnect
            };
        } catch (e) {
            console.debug("EventSource not supported or blocked:", e);
        }
    }

    /**
     * Setup Manual Update Sync Confirmation in Modal
     */
    function setupManualUpdateSync() {
        const btnSync = document.getElementById("btn-sync-local-version");
        const isEn = document.documentElement.lang === "en";

        if (!btnSync) return;

        btnSync.addEventListener("click", async function (e) {
            e.preventDefault();

            const modalNew = document.getElementById("modal-new-version");
            const targetVersion = modalNew ? modalNew.innerText.replace(/^v/i, "").trim() : "";

            const icon = btnSync.querySelector("[data-icon]");
            const spinner = btnSync.querySelector("[data-spinner]");
            const textEl = btnSync.querySelector("[data-text]");
            const originalText = textEl ? textEl.innerText : (isEn ? "I Have Updated (Sync Version)" : "Saya Sudah Update (Sinkronkan Versi)");
            const loadingText = btnSync.dataset.loadingText || (isEn ? "Syncing..." : "Menyinkronkan...");

            btnSync.disabled = true;
            if (icon) icon.classList.add("hidden");
            if (spinner) spinner.classList.remove("hidden");
            if (textEl) textEl.innerText = loadingText;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || "";
                const syncUrl = typeof route === "function"
                    ? route("app_version.sync-local")
                    : "/app-version/sync-local";

                const response = await fetch(syncUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({
                        version: targetVersion,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    cachedLocalVersion = data.app_version;
                    const modalCurr = document.getElementById("modal-curr-version");
                    if (modalCurr) modalCurr.innerText = `v${data.app_version}`;

                    // Hide update notification in navbar immediately
                    hideUpdateNotification();

                    if (window.Toast) {
                        window.Toast.success(window.langCustomAlert?.success ?? "Sukses", data.message || (isEn ? "Version synced successfully!" : "Versi berhasil disinkronkan!"));
                    }

                    // Close modal
                    if (typeof window.HSOverlay !== "undefined") {
                        window.HSOverlay.close("#modal-app-update");
                    }

                    // Dispatch global event
                    window.dispatchEvent(new CustomEvent("app-version:firebase-updated", {
                        detail: { fbData: currentFirebaseData, comparison: 0, localVersion: data.app_version }
                    }));
                } else {
                    if (window.Toast) {
                        window.Toast.error(window.langCustomAlert?.error ?? "Error", data.message || (isEn ? "Failed to sync version." : "Gagal menyinkronkan versi."));
                    }
                }
            } catch (err) {
                console.error("Sync version error:", err);
                if (window.Toast) {
                    window.Toast.error(window.langCustomAlert?.error ?? "Error", isEn ? "Failed to connect to server." : "Gagal terhubung ke server.");
                }
            } finally {
                btnSync.disabled = false;
                if (icon) icon.classList.remove("hidden");
                if (spinner) spinner.classList.add("hidden");
                if (textEl) textEl.innerText = originalText;
            }
        });
    }



    // Run on DOM ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => {
            initFirebaseRealtimeListener();
            setupManualUpdateSync();
        });
    } else {
        initFirebaseRealtimeListener();
        setupManualUpdateSync();
    }

    // Expose for manual trigger if needed
    window.recheckAppVersion = initFirebaseRealtimeListener;
})();

