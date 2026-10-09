import ApiProvider from "@/utils/api-provider";
import { startLoading, stopLoading } from "@/utils/button-loading";
import CustomAlert from "@/utils/custom-alert";
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
                CustomAlert.success("Status versi berhasil diperbarui!");
            }
        } catch (err) {
            console.error("Failed to refresh status:", err);
            CustomAlert.error(err.message || "Gagal memperbarui status versi.");
        } finally {
            stopLoading(btnRefresh);
            initLucide();
        }
    }

    const isEn = document.documentElement.lang === "en";

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
                CustomAlert.error(isEn ? "Version number cannot be empty." : "Nomor versi tidak boleh kosong.");
                return;
            }

            const formData = new FormData(form);
            const payload = {
                version: versionVal,
                release_name: formData.get("release_name") || "",
                changelog: formData.get("changelog") || "",
                update_guide: formData.get("update_guide") || "",
            };

            startLoading(btnSubmit);

            try {
                const response = await ApiProvider.post(route("app_version.publish"), payload);

                if (response.success) {
                    CustomAlert.success(response.message || (isEn ? "Version successfully published!" : "Versi berhasil dipublikasikan!"));
                    setTimeout(() => {
                        window.location.reload();
                    }, 1200);
                } else {
                    CustomAlert.error(response.message || (isEn ? "Failed to publish version." : "Gagal mempublikasikan versi."));
                }
            } catch (err) {
                console.error("Publish error:", err);
                const errMsg = err.response?.data?.message || err.message || (isEn ? "An error occurred while publishing." : "Terjadi kesalahan saat mempublikasikan versi.");
                CustomAlert.error(errMsg);
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

        renderSyncStatus(comparison, localVersion || displayLocal?.innerText?.replace(/^v/i, ""), fbData?.version);
        initLucide();
    });

    // -------------------------------------------------------------
    // Scripts Management (Save JSON & Execute via SSE Terminal)
    // -------------------------------------------------------------
    const formSaveScripts = document.getElementById("form-save-scripts-json");
    const textareaScripts = document.getElementById("textarea-scripts-json");
    const btnSaveScripts = document.getElementById("btn-save-scripts-json");
    const scriptRunButtons = document.querySelectorAll(".btn-run-script");

    const terminalRunnerTitle = document.getElementById("runner-terminal-title");
    const terminalRunnerBadge = document.getElementById("runner-status-badge");
    const terminalRunnerOutput = document.getElementById("runner-terminal-output");

    let activeScriptEventSource = null;

    // Save Scripts JSON
    if (formSaveScripts && textareaScripts) {
        formSaveScripts.addEventListener("submit", async function (e) {
            e.preventDefault();
            const rawJson = textareaScripts.value.trim();

            let parsed;
            try {
                parsed = JSON.parse(rawJson);
                if (!Array.isArray(parsed)) {
                    throw new Error(isEn ? "JSON root must be an array of scripts." : "Root JSON harus berupa array daftar script.");
                }
            } catch (jsonErr) {
                CustomAlert.error((isEn ? "Invalid JSON format: " : "Format JSON tidak valid: ") + jsonErr.message);
                return;
            }

            startLoading(btnSaveScripts);
            try {
                const response = await ApiProvider.post(route("app_version.scripts.save"), {
                    scripts: parsed,
                });

                if (response.success) {
                    CustomAlert.success(response.message || (isEn ? "Scripts successfully saved!" : "Daftar script berhasil disimpan!"));
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    CustomAlert.error(response.message || (isEn ? "Failed to save scripts." : "Gagal menyimpan script."));
                }
            } catch (err) {
                console.error("Save scripts error:", err);
                const errMsg = err.response?.data?.message || err.message || (isEn ? "An error occurred." : "Terjadi kesalahan saat menyimpan.");
                CustomAlert.error(errMsg);
            } finally {
                stopLoading(btnSaveScripts);
            }
        });
    }

    // Run Script via SSE Terminal
    function runScriptExecution(scriptKey) {
        if (!scriptKey) return;

        if (typeof HSOverlay !== "undefined") {
            HSOverlay.open("#modal-script-terminal");
        }

        if (terminalRunnerTitle) {
            terminalRunnerTitle.innerText = `deploy@an-mastery:~$ run-script (${scriptKey})`;
        }
        if (terminalRunnerBadge) {
            terminalRunnerBadge.innerText = isEn ? "Running..." : "Sedang Berjalan...";
            terminalRunnerBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400 animate-pulse";
        }
        if (terminalRunnerOutput) {
            terminalRunnerOutput.textContent = "";
        }

        if (activeScriptEventSource) {
            activeScriptEventSource.close();
            activeScriptEventSource = null;
        }

        let streamUrl = typeof route === "function"
            ? route("app_version.stream-update")
            : "/app-version/stream-update";

        const params = new URLSearchParams();
        params.set("script_key", scriptKey);
        streamUrl += (streamUrl.includes("?") ? "&" : "?") + params.toString();

        activeScriptEventSource = new EventSource(streamUrl);

        activeScriptEventSource.onmessage = function (event) {
            try {
                const data = JSON.parse(event.data);
                if (data.text && terminalRunnerOutput) {
                    terminalRunnerOutput.textContent += data.text;
                    terminalRunnerOutput.scrollTop = terminalRunnerOutput.scrollHeight;
                }

                if (data.type === "done") {
                    if (activeScriptEventSource) {
                        activeScriptEventSource.close();
                        activeScriptEventSource = null;
                    }
                    if (terminalRunnerBadge) {
                        terminalRunnerBadge.innerText = isEn ? "Completed (0)" : "Selesai (0)";
                        terminalRunnerBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-emerald-500/30 text-emerald-300 font-bold";
                    }
                    CustomAlert.success(isEn ? `Script '${scriptKey}' executed successfully.` : `Script '${scriptKey}' berhasil dijalankan.`);

                    if (data.new_version && displayLocal) {
                        displayLocal.innerText = `v${data.new_version}`;
                    }
                } else if (data.type === "error") {
                    if (activeScriptEventSource) {
                        activeScriptEventSource.close();
                        activeScriptEventSource = null;
                    }
                    if (terminalRunnerBadge) {
                        const badgeText = data.reason === "safety_preflight_failed"
                            ? (isEn ? "Safety Stopped" : "Dihentikan (Safety)")
                            : (isEn ? "Failed" : "Gagal");
                        terminalRunnerBadge.innerText = badgeText;
                        terminalRunnerBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-red-500/30 text-red-300 font-bold";
                    }
                    CustomAlert.error(data.message || (isEn ? `Script execution failed.` : `Eksekusi script gagal.`));
                }
            } catch (e) {
                if (terminalRunnerOutput) {
                    terminalRunnerOutput.textContent += event.data + "\n";
                    terminalRunnerOutput.scrollTop = terminalRunnerOutput.scrollHeight;
                }
            }
        };

        activeScriptEventSource.onerror = function (err) {
            console.error("SSE Terminal Runner Error:", err);
            if (activeScriptEventSource) {
                activeScriptEventSource.close();
                activeScriptEventSource = null;
            }
            if (terminalRunnerBadge) {
                terminalRunnerBadge.innerText = isEn ? "Connection Lost" : "Koneksi Terputus";
                terminalRunnerBadge.className = "text-[10px] font-mono px-2 py-0.5 rounded bg-neutral-700 text-neutral-300";
            }
        };
    }

    scriptRunButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const scriptKey = btn.getAttribute("data-script-key");
            runScriptExecution(scriptKey);
        });
    });

    // -------------------------------------------------------------
    // Single Script Edit / Add Modal Handlers
    // -------------------------------------------------------------
    const btnAddNewScript = document.getElementById("btn-add-new-script");
    const editSingleScriptButtons = document.querySelectorAll(".btn-edit-single-script");
    const formEditSingle = document.getElementById("form-edit-single-script");
    const inputScriptKey = document.getElementById("input-script-key");
    const inputScriptOriginalKey = document.getElementById("input-script-original-key");
    const inputScriptName = document.getElementById("input-script-name");
    const inputScriptIcon = document.getElementById("input-script-icon");
    const inputScriptDesc = document.getElementById("input-script-description");
    const textareaScriptContent = document.getElementById("textarea-script-content");
    const singleModalTitle = document.getElementById("single-script-modal-title");
    const btnDeleteSingle = document.getElementById("btn-delete-single-script");
    const btnSubmitSingle = document.getElementById("btn-submit-single-script");
    const singleMode = document.getElementById("single-script-mode");

    // Add New Script
    if (btnAddNewScript) {
        btnAddNewScript.addEventListener("click", () => {
            if (formEditSingle) formEditSingle.reset();
            if (singleMode) singleMode.value = "create";
            if (singleModalTitle) singleModalTitle.innerText = isEn ? "Add New Script" : "Tambah Script Baru";
            if (inputScriptOriginalKey) inputScriptOriginalKey.value = "";
            if (textareaScriptContent) {
                textareaScriptContent.value = "#!/usr/bin/env bash\n\necho 'Running script...'\nphp artisan optimize:clear\n";
            }
            if (btnDeleteSingle) btnDeleteSingle.classList.add("hidden");

            if (typeof HSOverlay !== "undefined") {
                HSOverlay.open("#modal-edit-single-script");
            }
        });
    }

    // Edit Single Script
    editSingleScriptButtons.forEach((btn) => {
        btn.addEventListener("click", async () => {
            const scriptKey = btn.getAttribute("data-script-key");
            if (!scriptKey) return;

            try {
                const response = await ApiProvider.get(route("app_version.scripts.show", { key: scriptKey }));
                if (response.success && response.data) {
                    const data = response.data;
                    const script = data.script || {};

                    if (formEditSingle) formEditSingle.reset();
                    if (singleMode) singleMode.value = "edit";
                    if (singleModalTitle) singleModalTitle.innerText = (isEn ? "Edit Script: " : "Edit Script: ") + (script.name || scriptKey);

                    if (inputScriptOriginalKey) {
                        inputScriptOriginalKey.value = script.key || scriptKey;
                    }
                    if (inputScriptKey) {
                        inputScriptKey.value = script.key || scriptKey;
                    }
                    if (inputScriptName) inputScriptName.value = script.name || "";
                    if (inputScriptIcon) inputScriptIcon.value = script.icon || "terminal";
                    if (inputScriptDesc) inputScriptDesc.value = script.description || "";
                    if (textareaScriptContent) {
                        textareaScriptContent.value = data.script_content || script.script_content || "";
                    }

                    if (btnDeleteSingle) {
                        btnDeleteSingle.classList.remove("hidden");
                        btnDeleteSingle.setAttribute("data-script-key", script.key || scriptKey);
                    }

                    if (typeof HSOverlay !== "undefined") {
                        HSOverlay.open("#modal-edit-single-script");
                    }
                }
            } catch (err) {
                console.error("Fetch script detail error:", err);
                CustomAlert.error(err.message || (isEn ? "Failed to load script details." : "Gagal memuat detail script."));
            }
        });
    });

    // Submit Single Script Form
    if (formEditSingle) {
        formEditSingle.addEventListener("submit", async function (e) {
            e.preventDefault();

            const payload = {
                original_key: inputScriptOriginalKey?.value?.trim() || "",
                key: inputScriptKey?.value?.trim(),
                name: inputScriptName?.value?.trim(),
                icon: inputScriptIcon?.value?.trim() || "terminal",
                description: inputScriptDesc?.value?.trim(),
                script_content: textareaScriptContent?.value?.trim(),
            };

            if (!payload.key || !payload.name || !payload.script_content) {
                CustomAlert.error(isEn ? "Please fill in key, name, and script content." : "Harap lengkapi key, nama script, dan isi kode script shell.");
                return;
            }

            startLoading(btnSubmitSingle);
            try {
                const response = await ApiProvider.post(route("app_version.scripts.save-single"), payload);
                if (response.success) {
                    CustomAlert.success(response.message || (isEn ? "Script saved successfully!" : "Script berhasil disimpan!"));
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    CustomAlert.error(response.message || (isEn ? "Failed to save script." : "Gagal menyimpan script."));
                }
            } catch (err) {
                console.error("Save single script error:", err);
                const errMsg = err.response?.data?.message || err.message || (isEn ? "An error occurred." : "Terjadi kesalahan.");
                CustomAlert.error(errMsg);
            } finally {
                stopLoading(btnSubmitSingle);
            }
        });
    }

    // Delete Single Script
    if (btnDeleteSingle) {
        btnDeleteSingle.addEventListener("click", async function () {
            const scriptKey = btnDeleteSingle.getAttribute("data-script-key");
            if (!scriptKey) return;

            const confirmed = await CustomAlert.confirm(
                isEn ? `Are you sure you want to delete script '${scriptKey}'?` : `Apakah Anda yakin ingin menghapus script '${scriptKey}'?`,
                isEn ? "This cannot be undone." : "Tindakan ini tidak dapat dibatalkan."
            );

            if (!confirmed) return;

            try {
                const response = await ApiProvider.delete(route("app_version.scripts.delete", { key: scriptKey }));
                if (response.success) {
                    CustomAlert.success(response.message || (isEn ? "Script deleted." : "Script berhasil dihapus."));
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    CustomAlert.error(response.message || (isEn ? "Failed to delete script." : "Gagal menghapus script."));
                }
            } catch (err) {
                console.error("Delete script error:", err);
                CustomAlert.error(err.message || (isEn ? "Failed to delete script." : "Gagal menghapus script."));
            }
        });
    }

    initLucide();
})();
