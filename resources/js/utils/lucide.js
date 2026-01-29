export function initLucide(root = document) {
    if (!window.lucide) return;

    window.lucide.createIcons({
        attrs: {
            "stroke-width": 1.75,
        },
        root,
    });
}
