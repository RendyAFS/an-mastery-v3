<div class="grid grid-cols-1 gap-6">

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.image') }}</label>

        <div id="existing-images" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
            @foreach ($gallery?->getMedia('galleries') ?? [] as $media)
                <div class="relative" data-media-id="{{ $media->id }}">
                    <img src="{{ $media->getUrl() }}" alt="{{ $gallery->name }}"
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
            class="inline-flex items-center py-2 px-4 text-sm font-medium rounded-lg cursor-pointer bg-(--color-primary) text-white hover:bg-(--color-primary)/80 focus:outline-none focus:ring-2 focus:ring-(--color-primary)/40 shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="camera" class="w-4 h-4 mr-2"></i>{{ __('filepond.take_photo') }}
        </button>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.name') }}</label>
        <input type="text" name="name" value="{{ isset($gallery) ? $gallery->name : '' }}"
            placeholder="{{ __('gallery.name_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.notes') }}</label>
        <textarea name="notes" rows="4" placeholder="{{ __('gallery.notes_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ isset($gallery) ? $gallery->notes : '' }}</textarea>
    </div>

</div>
