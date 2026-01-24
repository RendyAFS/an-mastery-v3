import Alpine from "alpinejs";

function customToast() {
    return {
        toasts: [],
        counter: 0,

        icons: {
            success: `<i data-lucide="circle-check-big" class="text-(--color-green) w-5 h-5"></i>`,
            info: `<i data-lucide="info" class="text-(--color-blue) w-5 h-5"></i>`,
            warning: `<i data-lucide="triangle-alert" class="text-(--color-yellow) w-5 h-5"></i>`,
            error: `<i data-lucide="circle-x" class="text-(--color-red) w-5 h-5"></i>`,
        },

        defaultTitles: {
            success: "Success",
            info: "Information",
            warning: "Warning",
            error: "Error",
        },

        show(message, type = "info", timeout = 4000, title = null) {
            const id = ++this.counter;
            const start = Date.now();

            this.toasts.push({
                id,
                title: title ?? this.defaultTitles[type],
                message,
                type,
                show: false,
                timeout,
                start,
                remaining: timeout,
                timer: null,
            });

            this.$nextTick(() => {
                const toast = this.toasts.find((t) => t.id === id);
                if (!toast) return;

                toast.show = true;
                this.startTimer(toast);

                if (window.lucide) {
                    window.lucide.createIcons();
                }
            });
        },

        startTimer(toast) {
            toast.timer = setTimeout(() => {
                this.remove(toast.id);
            }, toast.remaining);
        },

        pause(id) {
            const toast = this.toasts.find((t) => t.id === id);
            if (!toast || !toast.timer) return;

            clearTimeout(toast.timer);
            toast.timer = null;
            toast.remaining -= Date.now() - toast.start;
        },

        resume(id) {
            const toast = this.toasts.find((t) => t.id === id);
            if (!toast || toast.timer || toast.remaining <= 0) return;

            toast.start = Date.now();
            this.startTimer(toast);
        },

        remove(id) {
            const toast = this.toasts.find((t) => t.id === id);
            if (!toast) return;

            if (toast.timer) clearTimeout(toast.timer);

            toast.show = false;

            setTimeout(() => {
                this.toasts = this.toasts.filter((t) => t.id !== id);
                if (toast.onClose) toast.onClose();
            }, 250);
        },
    };
}

window.Toast = {
    success(title = null, message, timeout = 4000) {
        document.dispatchEvent(
            new CustomEvent("toast", { detail: { type: "success", title, message, timeout, }, }),
        );
    },
    error(title = null, message, timeout = 4000) {
        document.dispatchEvent(
            new CustomEvent("toast", { detail: { type: "error", title, message, timeout, }, }),
        );
    },
    info(title = null, message, timeout = 4000) {
        document.dispatchEvent(
            new CustomEvent("toast", { detail: { type: "info", title, message, timeout, }, }),
        );
    },
    warning(title = null, message, timeout = 4000) {
        document.dispatchEvent(
            new CustomEvent("toast", { detail: { type: "warning", title, message, timeout, }, }),
        );
    },
};

window.flashToast = function (type, title = null, message, timeout = 4000) {
    sessionStorage.setItem(
        "flash_toast",
        JSON.stringify({ type, title, message, timeout, }),
    );
};

document.addEventListener("DOMContentLoaded", () => {
    Object.keys(sessionStorage)
        .filter((key) => key.startsWith("flash_toast"))
        .forEach((key) => {
            const { type, title, message, timeout } = JSON.parse(
                sessionStorage.getItem(key),
            );

            setTimeout(() => {
                if (window.Toast?.[type]) {
                    window.Toast[type](title, message, timeout);
                }
            }, 350);

            sessionStorage.removeItem(key);
        });
});

Alpine.data("customToast", customToast);
Alpine.start();
export default customToast;
