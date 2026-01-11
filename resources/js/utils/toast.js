export default function customToast() {
    return {
        toasts: [],
        counter: 0,

        icons: {
            success: `<i data-lucide="circle-check-big" class="text-(--color-green) w-5 h-5"></i>`,
            info: `<i data-lucide="info" class="text-(--color-blue) w-5 h-5"></i>`,
            warning: `<i data-lucide="triangle-alert" class="text-(--color-yellow) w-5 h-5"></i>`,
            danger: `<i data-lucide="circle-x" class="text-(--color-red) w-5 h-5"></i>`,
        },

        defaultTitles: {
            success: "Success",
            info: "Information",
            warning: "Warning",
            danger: "Error",
        },

        show(message, type = "info", timeout = 3500, title = null) {
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

            if (toast.timer) {
                clearTimeout(toast.timer);
            }

            toast.show = false;

            setTimeout(() => {
                this.toasts = this.toasts.filter((t) => t.id !== id);
            }, 250);
        },
    };
}

window.Toast = {
    success(message, title = null, timeout) {
        document.dispatchEvent(
            new CustomEvent("toast", {
                detail: { message, type: "success", title, timeout },
            })
        );
    },
    info(message, title = null, timeout) {
        document.dispatchEvent(
            new CustomEvent("toast", {
                detail: { message, type: "info", title, timeout },
            })
        );
    },
    warning(message, title = null, timeout) {
        document.dispatchEvent(
            new CustomEvent("toast", {
                detail: { message, type: "warning", title, timeout },
            })
        );
    },
    danger(message, title = null, timeout) {
        document.dispatchEvent(
            new CustomEvent("toast", {
                detail: { message, type: "danger", title, timeout },
            })
        );
    },
};
