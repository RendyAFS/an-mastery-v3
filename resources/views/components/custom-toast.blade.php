<div x-data="customToast()" x-init="document.addEventListener('toast', (e) => { show(e.detail.message, e.detail.type, e.detail.timeout, e.detail.title) })" class="fixed top-5 right-5 z-50 flex flex-col items-end space-y-3">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show" @mouseenter="pause(toast.id)" @mouseleave="resume(toast.id)"
            x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition transform ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-3"
            class="w-max max-w-xs rounded-xl shadow-lg
                    bg-(--color-light) dark:bg-(--color-dark)
                    border border-(--color-dark) dark:border-(--color-gray)"
            role="alert">

            <div class="flex items-start gap-3 px-6 py-4">
                <!-- Icon + Message -->
                <div class="flex items-center gap-4 flex-1">
                    <div class="shrink-0" x-html="icons[toast.type]"></div>
                    <div class="text-sm text-(--color-dark) dark:text-(--color-light)">
                        <p class="font-semibold leading-tight" x-text="toast.title"></p>
                        <p class="mt-0.5 text-sm opacity-90" x-text="toast.message"></p>
                    </div>
                </div>

                <!-- Close Button -->
                <button @click="remove(toast.id)"
                    class="ms-3 p-2 rounded-full text-(--color-dark) dark:text-(--color-light)
                               hover:bg-(--color-gray)/30 transition ease-in-out duration-200 cursor-pointer">
                    <i data-lucide="x" class="text-(--color-dark)/50 dark:text-(--color-light)/70 w-4 h-4"></i>
                </button>
            </div>
        </div>
    </template>
</div>
