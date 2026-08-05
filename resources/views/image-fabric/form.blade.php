<div class="grid grid-cols-1 gap-6">

    {{-- Image Upload --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.image') }}</label>

        {{-- URL publik untuk fallback --}}
        <input type="hidden" id="image-preview"
            value="{{ isset($imageFabric) ? $imageFabric->getFirstMediaUrl('image-fabrics') : '' }}" />

        {{-- Path storage untuk server.load (agar preview bisa di-fetch) --}}
        <input type="hidden" id="image-path"
            value="{{ isset($imageFabric) ? optional($imageFabric->getFirstMedia('image-fabrics'))->getPath() : '' }}" />

        <input type="hidden" name="image_tmp" id="image_tmp"
            value="{{ isset($imageFabric) ? $imageFabric->getFirstMediaUrl('image-fabrics') : '' }}">
        <input type="hidden" name="remove_image" id="remove_image" value="0">
        <input type="file" name="image" class="filepond" accept="image/*" />
        <button type="button" id="camera-btn"
            class="px-4 py-2 rounded-lg border border-(--color-gray) text-sm text-(--color-dark) dark:text-(--color-light) dark:border-(--color-slate)">
            {{ __('filepond.take_photo') }}
        </button>
    </div>

    {{-- Name --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.name') }}</label>
        <input type="text" name="name" value="{{ isset($imageFabric) ? $imageFabric->name : '' }}"
            placeholder="{{ __('image-fabric.name_placeholder') }}" required
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    {{-- Notes --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">{{ __('image-fabric.fields.notes') }}</label>
        <textarea name="notes" rows="4" placeholder="{{ __('image-fabric.notes_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ isset($imageFabric) ? $imageFabric->notes : '' }}</textarea>
    </div>

</div>
