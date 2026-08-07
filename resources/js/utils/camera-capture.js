function createModal() {
    const overlay = document.createElement("div");
    overlay.className =
        "fixed inset-0 z-9999 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4";

    const cancelLabel = window.langFilepond?.cancel ?? "Cancel";
    const captureLabel = window.langFilepond?.take_photo ?? "Take Photo";
    const titleLabel = window.langFilepond?.camera_title ?? "Ambil Foto";

    overlay.innerHTML = `
        <div class="bg-(--color-light) dark:bg-(--color-dark-slate) rounded-2xl w-full max-w-3xl shadow-2xl overflow-hidden border border-(--color-light-gray) dark:border-(--color-slate)">
            <div class="flex justify-between items-center py-3 px-4 border-b border-(--color-light-gray) dark:border-(--color-slate)">
                <h3 class="font-semibold text-(--color-dark) dark:text-(--color-light)">${titleLabel}</h3>
                <button
                    type="button"
                    data-action="cancel"
                    class="size-8 inline-flex justify-center items-center rounded-full bg-(--color-light-gray) border border-(--color-gray) text-(--color-dark) hover:bg-(--color-gray)/40 dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light) transition-colors duration-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="relative bg-black">
                <video autoplay playsinline muted class="w-full aspect-video object-cover"></video>
                <canvas class="hidden"></canvas>

                <div class="absolute inset-0 flex items-center justify-center pointer-events-none" data-el="loading">
                    <div class="size-10 border-4 border-(--color-light)/30 border-t-(--color-light) rounded-full animate-spin"></div>
                </div>

                <button
                    type="button"
                    data-action="switch"
                    title="${window.langFilepond?.switch_camera ?? "Switch Camera"}"
                    class="hidden absolute top-3 right-3 size-10 items-center justify-center rounded-full bg-black/50 hover:bg-black/70 text-white backdrop-blur-sm transition-colors duration-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m17 2 4 4-4 4"/><path d="M3 11v-1a4 4 0 0 1 4-4h14"/><path d="m7 22-4-4 4-4"/><path d="M21 13v1a4 4 0 0 1-4 4H3"/></svg>
                </button>
            </div>

            <div class="flex justify-end gap-3 p-4 border-t border-(--color-light-gray) dark:border-(--color-slate)">
                <button
                    type="button"
                    data-action="cancel"
                    class="py-2 px-4 text-sm rounded-lg cursor-pointer border border-(--color-gray) bg-(--color-light) hover:bg-(--color-light-gray) text-(--color-primary) hover:text-(--color-primary) dark:bg-(--color-dark) dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate) dark:text-(--color-light) dark:hover:text-(--color-light) transition-colors duration-200">
                    ${cancelLabel}
                </button>

                <button
                    type="button"
                    data-action="capture"
                    class="py-2 px-4 text-sm rounded-lg cursor-pointer bg-(--color-success) hover:bg-(--color-success)/70 text-(--color-light) hover:text-(--color-light) transition-colors duration-200 inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    ${captureLabel}
                </button>
            </div>
        </div>
    `;

    return overlay;
}

async function openCameraCapture({ onCapture }) {
    const overlay = createModal();
    document.body.appendChild(overlay);

    const video = overlay.querySelector("video");
    const canvas = overlay.querySelector("canvas");
    const cancelBtns = overlay.querySelectorAll('[data-action="cancel"]');
    const captureBtn = overlay.querySelector('[data-action="capture"]');
    const switchBtn = overlay.querySelector('[data-action="switch"]');
    const loadingEl = overlay.querySelector('[data-el="loading"]');

    let stream = null;
    let currentFacingMode = "environment";
    let hasMultipleCameras = false;

    function cleanup() {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
        overlay.remove();
    }

    function setLoading(isLoading) {
        loadingEl.classList.toggle("hidden", !isLoading);
    }

    async function checkMultipleCameras() {
        try {
            const devices = await navigator.mediaDevices.enumerateDevices();
            hasMultipleCameras =
                devices.filter((d) => d.kind === "videoinput").length > 1;
            switchBtn.classList.toggle("hidden", !hasMultipleCameras);
            switchBtn.classList.toggle("flex", hasMultipleCameras);
        } catch (e) {
            hasMultipleCameras = false;
        }
    }

    async function startStream(facingMode) {
        setLoading(true);
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: facingMode } },
                audio: false,
            });
            video.srcObject = stream;
            currentFacingMode = facingMode;
        } catch (err) {
            Toast.error(
                window.langFilepond?.camera_error ?? "Failed to access camera",
            );
            cleanup();
            return;
        }
        setLoading(false);
    }

    await startStream(currentFacingMode);
    await checkMultipleCameras();

    cancelBtns.forEach((btn) => btn.addEventListener("click", cleanup));

    switchBtn.addEventListener("click", () => {
        const nextFacingMode =
            currentFacingMode === "environment" ? "user" : "environment";
        startStream(nextFacingMode);
    });

    captureBtn.addEventListener("click", () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext("2d");

        if (currentFacingMode === "user") {
            ctx.translate(canvas.width, 0);
            ctx.scale(-1, 1);
        }
        ctx.drawImage(video, 0, 0);

        canvas.toBlob((blob) => {
            if (!blob) return;
            const file = new File([blob], `camera-${Date.now()}.png`, {
                type: "image/png",
            });
            onCapture(file);
            cleanup();
        }, "image/png");
    });
}

export default openCameraCapture;
