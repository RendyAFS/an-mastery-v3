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
        const modalChangelogContainer = document.getElementById("modal-changelog-container");
        const modalChangelogContent = document.getElementById("modal-changelog-content");
        const modalGuideCmd = document.getElementById("modal-update-guide-cmd");

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
                modalPublishedMeta.innerText = isEn ? `Published: ${date}${by}` : `Dipublikasikan: ${date}${by}`;
            }
        }

        if (modalChangelogContainer && firebaseData.changelog) {
            modalChangelogContainer.classList.remove("hidden");
            if (modalChangelogContent) {
                modalChangelogContent.innerText = firebaseData.changelog;
            }
        }

        if (modalGuideCmd && firebaseData.update_guide) {
            modalGuideCmd.innerText = firebaseData.update_guide;
        }

        // Copy button setup
        const btnCopy = document.getElementById("btn-copy-update-cmd");
        if (btnCopy && modalGuideCmd) {
            btnCopy.onclick = function () {
                navigator.clipboard.writeText(modalGuideCmd.innerText).then(() => {
                    const originalHtml = btnCopy.innerHTML;
                    const copiedText = isEn ? "Copied!" : "Tersalin!";
                    btnCopy.innerHTML = `<i data-lucide="check" class="size-3 text-emerald-500"></i><span class="text-emerald-500">${copiedText}</span>`;
                    if (window.lucide) window.lucide.createIcons();
                    setTimeout(() => {
                        btnCopy.innerHTML = originalHtml;
                        if (window.lucide) window.lucide.createIcons();
                    }, 2000);
                });
            };
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
     * Setup Live Streaming Auto-Update Terminal in Modal
     */
    function setupAutoUpdateRunner() {
        const btnRun = document.getElementById("btn-run-auto-update");
        const btnBackup = document.getElementById("btn-run-modal-backup");
        const terminalWrapper = document.getElementById("modal-terminal-wrapper");
        const terminalOutput = document.getElementById("modal-terminal-output");
        const terminalBadge = document.getElementById("terminal-status-badge");
        const textRun = document.getElementById("text-run-update");
        const btnReload = document.getElementById("btn-reload-page");
        const btnCloseTerminal = document.getElementById("btn-close-terminal");

        let updateEventSource = null;
        const isEn = document.documentElement.lang === "en";

        if (btnCloseTerminal) {
            btnCloseTerminal.addEventListener("click", function () {
                if (updateEventSource) {
                    updateEventSource.close();
                    updateEventSource = null;
                }
                if (terminalWrapper) {
                    terminalWrapper.classList.add("hidden");
                }
                if (btnRun) btnRun.disabled = false;
                if (btnBackup) btnBackup.disabled = false;
                if (textRun) {
                    textRun.innerText = isEn ? "Run Update" : "Jalankan Update";
                }
            });
        }

        function runTerminalScript(scriptKey) {
            // Show Terminal Console
            if (terminalWrapper) {
                terminalWrapper.classList.remove("hidden");
            }
            if (terminalOutput) {
                terminalOutput.textContent = "";
            }

            if (btnRun) btnRun.disabled = true;
            if (btnBackup) btnBackup.disabled = true;

            if (textRun) textRun.innerText = isEn ? "Processing..." : "Memproses...";
            if (terminalBadge) {
                terminalBadge.innerText = "Running...";
                terminalBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 animate-pulse";
            }

            if (updateEventSource) {
                updateEventSource.close();
            }

            const modalNew = document.getElementById("modal-new-version");
            const targetVersion = modalNew ? modalNew.innerText.replace(/^v/i, "").trim() : "";

            let streamUrl = typeof route === "function"
                ? route("app_version.stream-update")
                : "/app-version/stream-update";

            const params = new URLSearchParams();
            params.set("script_key", scriptKey);
            if (targetVersion) {
                params.set("target_version", targetVersion);
            }
            streamUrl += (streamUrl.includes("?") ? "&" : "?") + params.toString();

            updateEventSource = new EventSource(streamUrl);

            updateEventSource.onmessage = function (event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.text && terminalOutput) {
                        terminalOutput.textContent += data.text;
                        terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    }

                    if (data.type === "done") {
                        if (updateEventSource) {
                            updateEventSource.close();
                            updateEventSource = null;
                        }
                        if (btnRun) btnRun.disabled = false;
                        if (btnBackup) btnBackup.disabled = false;

                        if (textRun) textRun.innerText = isEn ? "Done" : "Selesai";
                        if (terminalBadge) {
                            terminalBadge.innerText = isEn ? "Completed (0)" : "Selesai (0)";
                            terminalBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/30 text-emerald-300 font-bold";
                        }
                        if (btnReload) {
                            btnReload.classList.add("ring-4", "ring-emerald-400/50", "animate-bounce");
                        }

                        // If new version was applied, update local cache and UI
                        if (data.new_version) {
                            cachedLocalVersion = data.new_version;
                            const modalCurr = document.getElementById("modal-curr-version");
                            if (modalCurr) modalCurr.innerText = `v${data.new_version}`;
                            hideUpdateNotification();
                        }
                    } else if (data.type === "error") {
                        if (updateEventSource) {
                            updateEventSource.close();
                            updateEventSource = null;
                        }
                        if (btnRun) btnRun.disabled = false;
                        if (btnBackup) btnBackup.disabled = false;

                        if (textRun) textRun.innerText = isEn ? "Retry" : "Coba Lagi";
                        if (terminalBadge) {
                            const badgeText = data.reason === "safety_preflight_failed"
                                ? (isEn ? "Safety Stopped" : "Dihentikan (Safety)")
                                : (isEn ? "Failed" : "Gagal");
                            terminalBadge.innerText = badgeText;
                            terminalBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-red-500/30 text-red-300 font-bold";
                        }
                    }
                } catch (e) {
                    if (terminalOutput) {
                        terminalOutput.textContent += event.data + "\n";
                        terminalOutput.scrollTop = terminalOutput.scrollHeight;
                    }
                }
            };

            updateEventSource.onerror = function (err) {
                console.error("SSE Terminal Error:", err);
                if (updateEventSource) {
                    updateEventSource.close();
                    updateEventSource = null;
                }
                if (btnRun) btnRun.disabled = false;
                if (btnBackup) btnBackup.disabled = false;

                if (textRun) textRun.innerText = isEn ? "Retry" : "Coba Lagi";
                if (terminalBadge) {
                    terminalBadge.innerText = isEn ? "Connection Lost" : "Koneksi Terputus";
                    terminalBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-700 text-neutral-300";
                }
            };
        }

        if (btnRun) {
            btnRun.addEventListener("click", () => runTerminalScript("update_project"));
        }
        if (btnBackup) {
            btnBackup.addEventListener("click", () => runTerminalScript("backup_db"));
        }
    }

    /**
     * Setup Global Script Execution & Terminal Output Modal
     */
    function setupGlobalTerminalRunner() {
        let globalEventSource = null;
        let lastExecutedScriptKey = "update_project";
        const isEn = document.documentElement.lang === "en";

        const modalEl = document.getElementById("modal-global-script-terminal");
        const titleEl = document.getElementById("global-terminal-title");
        const statusEl = document.getElementById("global-terminal-status");
        const outputEl = document.getElementById("global-terminal-output");
        const btnClear = document.getElementById("btn-clear-global-terminal");
        const btnRerun = document.getElementById("btn-rerun-global-script");

        if (btnClear && outputEl) {
            btnClear.addEventListener("click", function () {
                outputEl.textContent = "";
            });
        }

        if (btnRerun) {
            btnRerun.addEventListener("click", function () {
                if (lastExecutedScriptKey) {
                    runGlobalScript(lastExecutedScriptKey);
                }
            });
        }

        function runGlobalScript(scriptKey, scriptName) {
            if (!scriptKey) return;
            lastExecutedScriptKey = scriptKey;

            // Open Preline Overlay Modal
            if (typeof window.HSOverlay !== "undefined" && modalEl) {
                window.HSOverlay.open(modalEl);
            } else {
                const trigger = document.querySelector('[data-hs-overlay="#modal-global-script-terminal"]');
                if (trigger) trigger.click();
            }

            if (titleEl) {
                titleEl.innerText = `deploy@an-mastery:~$ ./${scriptKey}.sh${scriptName ? ` (${scriptName})` : ""}`;
            }

            if (statusEl) {
                statusEl.innerText = isEn ? "Running..." : "Menjalankan...";
                statusEl.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 animate-pulse";
            }

            if (outputEl) {
                outputEl.textContent = `[Init] Starting script '${scriptKey}' via Database SOT...\n`;
            }

            if (globalEventSource) {
                globalEventSource.close();
                globalEventSource = null;
            }

            let streamUrl = typeof route === "function"
                ? route("app_version.stream-update")
                : "/app-version/stream-update";

            const params = new URLSearchParams();
            params.set("script_key", scriptKey);
            streamUrl += (streamUrl.includes("?") ? "&" : "?") + params.toString();

            globalEventSource = new EventSource(streamUrl);

            globalEventSource.onmessage = function (event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.text && outputEl) {
                        outputEl.textContent += data.text;
                        outputEl.scrollTop = outputEl.scrollHeight;
                    }

                    if (data.type === "done") {
                        if (globalEventSource) {
                            globalEventSource.close();
                            globalEventSource = null;
                        }
                        if (statusEl) {
                            statusEl.innerText = isEn ? "Completed (0)" : "Selesai (0)";
                            statusEl.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/30 text-emerald-300 font-bold";
                        }
                        if (data.new_version) {
                            cachedLocalVersion = data.new_version;
                            const modalCurr = document.getElementById("modal-curr-version");
                            if (modalCurr) modalCurr.innerText = `v${data.new_version}`;
                            hideUpdateNotification();
                        }
                    } else if (data.type === "error") {
                        if (globalEventSource) {
                            globalEventSource.close();
                            globalEventSource = null;
                        }
                        if (statusEl) {
                            const badgeText = data.reason === "safety_preflight_failed"
                                ? (isEn ? "Safety Stopped" : "Dihentikan (Safety)")
                                : (isEn ? "Failed" : "Gagal");
                            statusEl.innerText = badgeText;
                            statusEl.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-red-500/30 text-red-300 font-bold";
                        }
                    }
                } catch (e) {
                    if (outputEl) {
                        outputEl.textContent += event.data + "\n";
                        outputEl.scrollTop = outputEl.scrollHeight;
                    }
                }
            };

            globalEventSource.onerror = function (err) {
                console.error("Global SSE Terminal Error:", err);
                if (globalEventSource) {
                    globalEventSource.close();
                    globalEventSource = null;
                }
                if (statusEl) {
                    statusEl.innerText = isEn ? "Connection Lost" : "Koneksi Terputus";
                    statusEl.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-700 text-neutral-300";
                }
            };
        }

        // Delegated click listener for any button with class .btn-run-global-script
        document.addEventListener("click", function (e) {
            const btn = e.target.closest(".btn-run-global-script");
            if (btn) {
                e.preventDefault();
                const key = btn.dataset.scriptKey;
                const name = btn.dataset.scriptName || "";
                if (key) {
                    runGlobalScript(key, name);
                }
            }
        });

        window.runGlobalScriptTerminal = runGlobalScript;
    }

    // Run on DOM ready
    if (document.readyState === "loading") {
        document.addEventListener("DOMContentLoaded", () => {
            initFirebaseRealtimeListener();
            setupAutoUpdateRunner();
            setupGlobalTerminalRunner();
        });
    } else {
        initFirebaseRealtimeListener();
        setupAutoUpdateRunner();
        setupGlobalTerminalRunner();
    }

    // Expose for manual trigger if needed
    window.recheckAppVersion = initFirebaseRealtimeListener;
})();

