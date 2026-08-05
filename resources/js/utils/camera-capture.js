function createModal() {
    const overlay = document.createElement("div");
    overlay.className =
        "fixed inset-0 z-9999 flex items-center justify-center bg-black/70";

    const cancelLabel = window.langFilepond?.cancel ?? "Cancel";
    const captureLabel = window.langFilepond?.take_photo ?? "Take Photo";

    overlay.innerHTML = `
        <div class="bg-white dark:bg-(--color-dark-slate) rounded-lg p-4 w-full max-w-xl space-y-4">
            <video autoplay playsinline class="w-full rounded-lg bg-black"></video>
            <canvas class="hidden"></canvas>
            <div class="flex justify-end gap-2">
                <button type="button" data-action="cancel" class="px-4 py-2 rounded-lg border border-(--color-gray)">${cancelLabel}</button>
                <button type="button" data-action="capture" class="px-4 py-2 rounded-lg bg-(--color-primary) text-white">${captureLabel}</button>
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
    const cancelBtn = overlay.querySelector('[data-action="cancel"]');
    const captureBtn = overlay.querySelector('[data-action="capture"]');

    let stream = null;

    function cleanup() {
        if (stream) {
            stream.getTracks().forEach((track) => track.stop());
        }
        overlay.remove();
    }

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" },
            audio: false,
        });
        video.srcObject = stream;
    } catch (err) {
        Toast.error(
            window.langFilepond?.camera_error ?? "Failed to access camera",
        );
        cleanup();
        return;
    }

    cancelBtn.addEventListener("click", cleanup);

    captureBtn.addEventListener("click", () => {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext("2d").drawImage(video, 0, 0);

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
