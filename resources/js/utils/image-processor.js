async function loadImage(file) {
    return new Promise((resolve, reject) => {
        const img = new Image();
        const url = URL.createObjectURL(file);
        img.onload = () => {
            URL.revokeObjectURL(url);
            resolve(img);
        };
        img.onerror = (e) => {
            URL.revokeObjectURL(url);
            reject(e);
        };
        img.src = url;
    });
}

function canvasToBlob(canvas, mimeType) {
    return new Promise((resolve) => {
        canvas.toBlob((blob) => resolve(blob), mimeType);
    });
}

async function processImageFile(
    file,
    {
        maxSizeBytes = 2 * 1024 * 1024,
        mimeType = "image/png",
        minDimension = 320,
    } = {},
) {
    const img = await loadImage(file);

    let width = img.naturalWidth || img.width;
    let height = img.naturalHeight || img.height;

    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");

    let blob = null;

    while (true) {
        canvas.width = width;
        canvas.height = height;
        ctx.clearRect(0, 0, width, height);
        ctx.drawImage(img, 0, 0, width, height);

        blob = await canvasToBlob(canvas, mimeType);

        if (!blob) break;
        if (blob.size <= maxSizeBytes) break;
        if (width <= minDimension || height <= minDimension) break;

        width = Math.round(width * 0.85);
        height = Math.round(height * 0.85);
    }

    const fileName = file.name
        ? file.name.replace(/\.[^/.]+$/, "") + ".png"
        : `image-${Date.now()}.png`;

    return new File([blob], fileName, { type: mimeType });
}

export default processImageFile;
