<div class="grid grid-cols-1 gap-4">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('workshop.fields.image') }}
        </label>

        <div id="existing-images" class="grid grid-cols-3 sm:grid-cols-4 gap-3"></div>

        <input type="hidden" name="images_tmp" id="images_tmp" value="[]">
        <input type="hidden" name="removed_images" id="removed_images" value="[]">
        <input type="file" name="images" id="workshop-filepond" class="filepond" accept="image/*" multiple />

        <button type="button" id="camera-btn"
            class="inline-flex items-center py-2 px-4 text-sm font-medium rounded-lg cursor-pointer bg-(--color-primary) text-white hover:bg-(--color-primary)/80 focus:outline-none focus:ring-2 focus:ring-(--color-primary)/40 shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="camera" class="w-4 h-4 mr-2"></i>{{ __('filepond.take_photo') }}
        </button>
    </div>

    <div class="space-y-2">
        <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('workshop.fields.name') }} <span class="text-(--color-danger)">*</span>
        </label>
        <input type="text" id="name" name="name"
            placeholder="{{ __('workshop.name_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
    </div>

    <div class="space-y-2">
        <label for="location" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
            {{ __('workshop.fields.location') }}
        </label>
        <textarea id="location" name="location" rows="3"
            placeholder="{{ __('workshop.location_placeholder') }}"
            class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)"></textarea>
    </div>

    <div class="flex items-center">
        <input type="checkbox" id="is_active" name="is_active" value="1" class="checkbox-custom" checked>
        <label for="is_active"
            class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) ms-3 cursor-pointer">
            {{ __('workshop.fields.is_active') }}
        </label>
    </div>
</div>
