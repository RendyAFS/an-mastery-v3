export default function fixTextareaEnter() {
    document.addEventListener(
        "keydown",
        (e) => {
            if (e.target.tagName === "TEXTAREA" && e.key === "Enter") {
                e.stopImmediatePropagation();
            }
        },
        true,
    );
}
