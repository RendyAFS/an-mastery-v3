@props([
    'top' => 'top-22',
    'right' => 'right-4',
])

<div x-data="customAlert()"
    x-init="
        document.addEventListener('toast', (e) => { showToast(e.detail.message, e.detail.type, e.detail.timeout, e.detail.title) });
        document.addEventListener('alert', (e) => { showAlert(e.detail.title, e.detail.message, e.detail.type, e.detail.confirmText) });
        document.addEventListener('confirm', (e) => { showConfirm(e.detail.title, e.detail.message, e.detail.confirmText, e.detail.cancelText, e.detail.onConfirm, e.detail.onCancel) });
    ">

    {{-- Toast Container --}}
    <div class="fixed {{ $top }} {{ $right }} z-50 flex flex-col gap-3 w-max max-w-xs">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" @mouseenter="pauseToast(toast.id)" @mouseleave="resumeToast(toast.id)"
                x-transition:enter="transition transform ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition transform ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-3"
                class="w-80 rounded-xl shadow-lg bg-(--color-light) dark:bg-(--color-dark) border border-(--color-gray)"
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
                    <button @click="removeToast(toast.id)"
                        class="ms-3 p-2 rounded-full text-(--color-dark) dark:text-(--color-light) bg-(--color-gray)/20
                                   hover:bg-(--color-gray)/40 transition ease-in-out duration-200 cursor-pointer">
                        <i data-lucide="x" class="text-(--color-dark)/70 dark:text-(--color-light)/70 size-4"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>

    {{-- Alert Modal --}}
    <div x-show="alert.show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-black/50 backdrop-blur-sm"
        style="display: none;">

        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="alert.show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-md bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow-2xl border border-(--color-gray)"
                @click.away="closeAlert()">

                {{-- Header --}}
                <div class="flex justify-between items-start p-5 border-b border-(--color-gray)">
                    <div class="flex items-center gap-3">
                        <div x-html="icons[alert.type]"></div>
                        <h3 class="font-semibold text-lg text-(--color-dark) dark:text-(--color-light)" x-text="alert.title"></h3>
                    </div>
                    <button @click="closeAlert()"
                        class="p-2 rounded-full text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20 transition">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <p class="text-(--color-dark) dark:text-(--color-light) opacity-90" x-text="alert.message"></p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 p-5 border-t border-(--color-gray)">
                    <button @click="closeAlert()"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-(--color-gray)/20 text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/30 transition"
                        x-text="alert.confirmText">
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Confirm Modal --}}
    <div x-show="confirm.show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[80] overflow-x-hidden overflow-y-auto bg-black/50 backdrop-blur-sm"
        style="display: none;">

        <div class="min-h-full flex items-center justify-center p-4">
            <div x-show="confirm.show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-md bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow-2xl border border-(--color-gray)"
                @click.away="closeConfirm(false)">

                {{-- Header --}}
                <div class="flex justify-between items-start p-5 border-b border-(--color-gray)">
                    <div class="flex items-center gap-3">
                        <div x-html="icons.warning"></div>
                        <h3 class="font-semibold text-lg text-(--color-dark) dark:text-(--color-light)" x-text="confirm.title"></h3>
                    </div>
                    <button @click="closeConfirm(false)"
                        class="p-2 rounded-full text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/20 transition">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-5">
                    <p class="text-(--color-dark) dark:text-(--color-light) opacity-90" x-text="confirm.message"></p>
                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-2 p-5 border-t border-(--color-gray)">
                    <button @click="closeConfirm(false)"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-(--color-gray)/20 text-(--color-dark) dark:text-(--color-light) hover:bg-(--color-gray)/30 transition"
                        x-text="confirm.cancelText">
                    </button>
                    <button @click="closeConfirm(true)"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-(--color-red) text-white hover:bg-(--color-red)/90 transition"
                        x-text="confirm.confirmText">
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
