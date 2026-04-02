<div class="grid grid-cols-1 gap-6">

    {{-- Image Upload --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">Image</label>

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
    </div>

    {{-- Name --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">Name</label>
        <input type="text" name="name" value="{{ isset($imageFabric) ? $imageFabric->name : '' }}"
            placeholder="Enter name" required
            class="w-full rounded-lg px-4 py-2
                   bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                   focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30">
    </div>

    {{-- Notes --}}
    <div class="space-y-2">
        <label class="block text-sm font-medium">Notes</label>
        <textarea name="notes" rows="4" placeholder="Enter notes"
            class="w-full rounded-lg px-4 py-2
                   bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                   focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30">{{ isset($imageFabric) ? $imageFabric->notes : '' }}</textarea>
    </div>

</div>
