<div class="grid grid-cols-1 gap-6">

    {{-- Image Upload --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.image') }}</label>

        {{-- URL publik untuk fallback --}}
        <input type="hidden" id="image-preview"
            value="{{ isset($gallery) ? $gallery->getFirstMediaUrl('galleries') : '' }}" />

        {{-- Path storage untuk server.load (agar preview bisa di-fetch) --}}
        <input type="hidden" id="image-path"
            value="{{ isset($gallery) ? optional($gallery->getFirstMedia('galleries'))->getPath() : '' }}" />

        <input type="hidden" name="image_tmp" id="image_tmp"
            value="{{ isset($gallery) ? $gallery->getFirstMediaUrl('galleries') : '' }}">
        <input type="hidden" name="remove_image" id="remove_image" value="0">
        <input type="file" name="image" class="filepond" accept="image/*" />
        <button type="button" id="camera-btn"
            class="py-2 px-4 text-sm flex items-center rounded-lg cursor-pointer border border-(--color-gray) bg-(--color-light) hover:bg-(--color-light-gray) text-(--color-primary) hover:text-(--color-primary) dark:bg-(--color-dark) dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate) dark:text-(--color-light) dark:hover:text-(--color-light) transition-colors duration-200">
            <i data-lucide="camera" class="w-4 h-4 mr-2"></i>{{ __('filepond.take_photo') }}
        </button>
    </div>

    {{-- Name --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.name') }}</label>
        <input type="text" name="name" value="{{ isset($gallery) ? $gallery->name : '' }}"
            placeholder="{{ __('gallery.name_placeholder') }}" required
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    {{-- Notes --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('gallery.fields.notes') }}</label>
        <textarea name="notes" rows="4" placeholder="{{ __('gallery.notes_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ isset($gallery) ? $gallery->notes : '' }}</textarea>
    </div>

</div>
