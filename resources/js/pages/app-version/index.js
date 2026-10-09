import ApiProvider from "@/utils/api-provider";
import { startLoading, stopLoading } from "@/utils/button-loading";
import { initLucide } from "@/utils/lucide";

(function () {
    "use strict";

    const form = document.getElementById("form-publish-version");
    const inputVersion = document.getElementById("input-version");
    const btnSubmit = document.getElementById("btn-submit-publish");
    const btnRefresh = document.getElementById("btn-refresh-status");
    const incButtons = document.querySelectorAll(".btn-increment");

    // Display elements
    const displayLocal = document.getElementById("display-local-version");
    const displayFirebase = document.getElementById("display-firebase-version");
    const displayFirebaseMeta = document.getElementById("display-firebase-meta");
    const displaySyncStatus = document.getElementById("display-sync-status");

    const initialLocalVersion = (displayLocal?.innerText || inputVersion?.value || "1.0.0").replace(/^v/i, "").trim();

    // Helper: parse semantic version
    function parseSemVer(v) {
        const clean = (v || "1.0.0").replace(/^v/i, "").trim();
        const parts = clean.split("-")[0].split(".");
        return {
            major: parseInt(parts[0], 10) || 0,
            minor: parseInt(parts[1], 10) || 0,
            patch: parseInt(parts[2], 10) || 0,
        };
    }

    // Helper: increment / reset version
    function handleVersionIncrement(type) {
        if (type === "reset") {
            const currentActive = (displayLocal?.innerText || initialLocalVersion).replace(/^v/i, "").trim();
            inputVersion.value = currentActive;
            return;
        }

        const parsed = parseSemVer(inputVersion.value || displayLocal?.innerText || "1.0.0");
        if (type === "patch") {
            parsed.patch += 1;
        } else if (type === "minor") {
            parsed.minor += 1;
            parsed.patch = 0;
        } else if (type === "major") {
            parsed.major += 1;
            parsed.minor = 0;
            parsed.patch = 0;
        }
        inputVersion.value = `${parsed.major}.${parsed.minor}.${parsed.patch}`;
    }

    incButtons.forEach((btn) => {
        btn.addEventListener("click", () => {
            const incType = btn.getAttribute("data-inc");
            handleVersionIncrement(incType);
        });
    });

    const isEn = document.documentElement.lang === "en";

    // Refresh status
    async function refreshStatus() {
        if (!btnRefresh) return;
        startLoading(btnRefresh);

        try {
            const res = await ApiProvider.get(route("app_version.index"));
            if (res && res.current_version) {
                if (displayLocal) {
                    displayLocal.innerText = `v${res.current_version}`;
                }

                if (res.firebase_data && res.firebase_data.version) {
                    if (displayFirebase) {
                        displayFirebase.innerText = `v${res.firebase_data.version}`;
                    }
                    if (displayFirebaseMeta) {
                        displayFirebaseMeta.innerHTML = `Versi publikasi aktif di Firebase`;
                    }
                } else {
                    if (displayFirebase) displayFirebase.innerText = "-";
                    if (displayFirebaseMeta) {
                        displayFirebaseMeta.innerText = "Firebase belum merespons atau belum ada versi";
                    }
                }

                renderSyncStatus(res.comparison, res.current_version, res.firebase_data?.version);
                if (window.Toast) {
                    window.Toast.success(window.langCustomAlert?.success ?? "Sukses", isEn ? "Version status refreshed!" : "Status versi berhasil diperbarui!");
                }
            }
        } catch (err) {
            console.error("Failed to refresh status:", err);
            if (window.Toast) {
                window.Toast.error(window.langCustomAlert?.error ?? "Error", err.message || (isEn ? "Failed to refresh status." : "Gagal memperbarui status versi."));
            }
        } finally {
            stopLoading(btnRefresh);
            initLucide();
        }
    }

    function renderSyncStatus(comparison, localV, fbV) {
        if (!displaySyncStatus) return;

        if (comparison === null || comparison === undefined) {
            displaySyncStatus.innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-yellow)/10 text-(--color-yellow) text-sm font-semibold">
                    <i data-lucide="alert-circle" class="size-4"></i>
                    <span>${isEn ? "Not Connected" : "Belum Terhubung"}</span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                    ${isEn ? "Firebase has not responded or no version has been published yet." : "Firebase belum merespons atau belum ada versi yang dipublikasikan."}
                </p>
            `;
        } else if (comparison === 0) {
            displaySyncStatus.innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-green)/10 text-(--color-green) text-sm font-semibold">
                    <i data-lucide="check-circle-2" class="size-4"></i>
                    <span>${isEn ? "Application Up-to-Date" : "Aplikasi Up-to-Date"}</span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                    ${isEn ? `Local version matches Firebase publication exactly (v${localV}).` : `Versi lokal sama persis dengan versi publikasi Firebase (v${localV}).`}
                </p>
            `;
        } else if (comparison === -1) {
            displaySyncStatus.innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-primary)/15 text-(--color-primary) text-sm font-semibold">
                    <i data-lucide="arrow-up-circle" class="size-4"></i>
                    <span>${isEn ? "Update Available" : "Pembaruan Tersedia"}</span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                    ${isEn ? `Version in Firebase (v${fbV}) is newer than this server (v${localV}).` : `Versi di Firebase (v${fbV}) lebih baru dari server ini (v${localV}).`}
                </p>
            `;
        } else {
            displaySyncStatus.innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-(--color-blue)/10 text-(--color-blue) text-sm font-semibold">
                    <i data-lucide="code" class="size-4"></i>
                    <span>${isEn ? "Development Mode" : "Mode Development"}</span>
                </div>
                <p class="text-xs text-(--color-dark-gray) dark:text-(--color-gray) mt-2">
                    ${isEn ? `Local server is running on a version not yet published (v${localV}).` : `Server lokal berjalan pada versi yang belum dipublikasikan (v${localV}).`}
                </p>
            `;
        }
    }

    if (btnRefresh) {
        btnRefresh.addEventListener("click", refreshStatus);
    }

    // Submit Publish Form
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const versionVal = (inputVersion.value || "").trim();
            if (!versionVal) {
                if (window.Toast) {
                    window.Toast.error(window.langCustomAlert?.error ?? "Error", isEn ? "Version number cannot be empty." : "Nomor versi tidak boleh kosong.");
                }
                return;
            }

            const formData = new FormData(form);
            const payload = {
                version: versionVal,
                release_name: formData.get("release_name") || "",
                description: formData.get("description") || "",
                changelog: formData.get("changelog") || "",
            };

            startLoading(btnSubmit);

            try {
                const response = await ApiProvider.post(route("app_version.publish"), payload);

                if (response.success) {
                    if (window.Toast) {
                        window.Toast.success(window.langCustomAlert?.success ?? "Sukses", response.message || (isEn ? "Version successfully published!" : "Versi berhasil dipublikasikan!"));
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    if (window.Toast) {
                        window.Toast.error(window.langCustomAlert?.error ?? "Error", response.message || (isEn ? "Failed to publish version." : "Gagal mempublikasikan versi."));
                    }
                }
            } catch (err) {
                console.error("Publish error:", err);
                const errMsg = err.response?.data?.message || err.message || (isEn ? "An error occurred while publishing." : "Terjadi kesalahan saat mempublikasikan versi.");
                if (window.Toast) {
                    window.Toast.error(window.langCustomAlert?.error ?? "Error", errMsg);
                }
            } finally {
                stopLoading(btnSubmit);
            }
        });
    }

    // Realtime Listener for Firebase RTDB updates
    window.addEventListener("app-version:firebase-updated", (event) => {
        const { fbData, comparison, localVersion } = event.detail || {};
        if (fbData && fbData.version) {
            if (displayFirebase) {
                displayFirebase.innerText = `v${fbData.version}`;
            }
            if (displayFirebaseMeta) {
                let metaText = isEn ? "Active publication version in Firebase" : "Versi publikasi aktif di Firebase";
                if (fbData.published_at) {
                    const dateStr = new Date(fbData.published_at).toLocaleString();
                    metaText = isEn ? `Published: ${dateStr}` : `Dipublikasikan: ${dateStr}`;
                    if (fbData.published_by) {
                        metaText += isEn ? ` by <span class="font-medium text-(--color-dark) dark:text-(--color-light)">${fbData.published_by}</span>` : ` oleh <span class="font-medium text-(--color-dark) dark:text-(--color-light)">${fbData.published_by}</span>`;
                    }
                }
                displayFirebaseMeta.innerHTML = metaText;
            }
        } else {
            if (displayFirebase) displayFirebase.innerText = "-";
            if (displayFirebaseMeta) {
                displayFirebaseMeta.innerText = isEn ? "Firebase unreachable or no published version" : "Firebase belum merespons atau belum ada versi";
            }
        }

        initLucide();
    });

    initLucide();
})();
