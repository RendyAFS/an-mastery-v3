<div class="grid grid-cols-1 gap-6">

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.image') }}</label>

        <div id="existing-images" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
            @foreach ($imageFabric?->getMedia('image-fabrics') ?? [] as $media)
                <div class="relative" data-media-id="{{ $media->id }}">
                    <img src="{{ $media->getUrl() }}" alt="{{ $imageFabric->name }}"
                        class="w-full aspect-square object-cover rounded-lg border border-(--color-gray)">
                    <button type="button" data-remove-existing="{{ $media->id }}"
                        class="absolute -top-2 -right-2 size-6 flex items-center justify-center rounded-full
                            bg-(--color-danger) text-white shadow cursor-pointer">
                        <i data-lucide="x" class="size-3.5"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <input type="hidden" name="images_tmp" id="images_tmp" value="[]">
        <input type="hidden" name="removed_images" id="removed_images" value="[]">
        <input type="file" name="images" class="filepond" accept="image/*" multiple />
        <button type="button" id="camera-btn"
            class="py-2 px-4 text-sm flex items-center rounded-lg cursor-pointer border border-(--color-gray) bg-(--color-light) hover:bg-(--color-light-gray) text-(--color-primary) hover:text-(--color-primary) dark:bg-(--color-dark) dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate) dark:text-(--color-light) dark:hover:text-(--color-light) transition-colors duration-200">
            <i data-lucide="camera" class="w-4 h-4 mr-2"></i>{{ __('filepond.take_photo') }}
        </button>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.name') }}</label>
        <input type="text" name="name" value="{{ isset($imageFabric) ? $imageFabric->name : '' }}"
            placeholder="{{ __('image-fabric.name_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.notes') }}</label>
        <textarea name="notes" rows="4" placeholder="{{ __('image-fabric.notes_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ isset($imageFabric) ? $imageFabric->notes : '' }}</textarea>
    </div>

</div>
