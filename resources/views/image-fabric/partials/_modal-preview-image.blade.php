<x-modal id="hs-image-fabric-viewer" title="{{ __('models.ImageFabric') }}" size="lg" :scrollable="false" :centered="true">
    <div
        class="relative h-[70vh] flex items-center justify-center bg-(--color-dark)/5 dark:bg-white/5 rounded-lg overflow-hidden">
        <button type="button" id="viewer-prev"
            class="absolute left-2 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center rounded-full
            bg-(--color-dark)/60 text-white hover:bg-(--color-dark)/80 cursor-pointer z-10">
            <i data-lucide="chevron-left" class="size-5"></i>
        </button>

        <img id="viewer-image" src="" alt="" class="max-w-full max-h-full w-auto h-auto object-contain">

        <button type="button" id="viewer-next"
            class="absolute right-2 top-1/2 -translate-y-1/2 size-9 flex items-center justify-center rounded-full
            bg-(--color-dark)/60 text-white hover:bg-(--color-dark)/80 cursor-pointer z-10">
            <i data-lucide="chevron-right" class="size-5"></i>
        </button>
    </div>
    <p id="viewer-counter" class="text-center text-sm text-(--color-dark-gray) mt-2"></p>
</x-modal>
