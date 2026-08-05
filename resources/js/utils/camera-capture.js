function createModal() {
    const overlay = document.createElement("div");
    overlay.className =
        "fixed inset-0 z-9999 flex items-center justify-center bg-black/70";

    const cancelLabel = window.langFilepond?.cancel ?? "Cancel";
    const captureLabel = window.langFilepond?.take_photo ?? "Take Photo";

    overlay.innerHTML = `
        <div class="bg-(--color-light) dark:bg-(--color-dark-slate) rounded-lg p-6 w-full max-w-3xl space-y-4 shadow-xl">
            <video autoplay playsinline class="w-full aspect-video rounded-lg bg-black object-cover"></video>
            <canvas class="hidden"></canvas>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    data-action="cancel"
                    class=" py-2 px-4 text-sm rounded-lg cursor-pointer border border-(--color-gray) bg-(--color-light) hover:bg-(--color-light-gray) text-(--color-primary) hover:text-(--color-primary) dark:bg-(--color-dark) dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate) dark:text-(--color-light) dark:hover:text-(--color-light) transition-colors duration-200">
                    ${cancelLabel}
                </button>

                <button
                    type="button"
                    data-action="capture"
                    class=" py-2 px-4 text-sm rounded-lg cursor-pointer bg-(--color-success) hover:bg-(--color-success)/70 text-(--color-light) hover:text-(--color-light) transition-colors duration-200">
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
