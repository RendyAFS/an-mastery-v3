const Loading = {
    start(message = "Loading...") {
        $.blockUI({
            message: `
                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="animate-spin rounded-full h-10 w-10 border-t-4 border-b-4 border-(--color-primary)"></div>
                    <span class="text-(--color-light) font-semibold text-lg">${message}</span>
                </div>
            `,
            baseZ: 999999,
            css: {
                border: "none",
                backgroundColor: "transparent",
                cursor: "wait",
                padding: "0",
            },
            overlayCSS: {
                backgroundColor: "rgba(0,0,0,0.90)",
                cursor: "wait",
            },
        });
    },
    stop() {
        $.unblockUI();
    },
};

export default Loading;
