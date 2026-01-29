<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="col-span-1">
        <div class="mb-6 space-y-2">
            <label for="name" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Name
            </label>

            <input type="text" id="name" name="name" value="{{ $user->name ?? '' }}" required
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-6 space-y-2">
            <label for="email" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Email
            </label>

            <input type="email" id="email" name="email" value="{{ $user->email ?? '' }}" required
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-6 space-y-2" x-data="{ show: false }">
            <label for="password" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Password
            </label>

            <div class="relative mt-1">
                <input :type="show ? 'text' : 'password'" name="password" id="password" autocomplete="false"
                    class="peer w-full py-2.5 px-4 rounded-lg block
                   bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                   border border-(--color-gray) dark:border-(--color-slate)
                   text-(--color-dark) dark:text-(--color-light)
                   focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   disabled:opacity-50 disabled:pointer-events-none"
                    placeholder="Enter password">

                <button type="button" @click="show = !show" tabindex="-1"
                    class="absolute inset-y-0 end-4 flex items-center text-(--color-primary)/70 hover:text-(--color-primary) transition cursor-pointer">
                    <i x-show="!show" data-lucide="eye-off" class="size-5"></i>
                    <i x-show="show" data-lucide="eye" class="size-5"></i>
                </button>
            </div>

            @isset($user)
                <small class="text-xs text-(--color-dark-gray)">
                    Kosongkan jika tidak ingin mengubah password
                </small>
            @endisset
        </div>

        <div class="mb-6 space-y-2" x-data="{ showConfirm: false }">
            <label for="password_confirmation"
                class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Confirm Password
            </label>

            <div class="relative mt-1">
                <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                    autocomplete="false"
                    class="peer w-full py-2.5 px-4 rounded-lg block
                   bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                   border border-(--color-gray) dark:border-(--color-slate)
                   text-(--color-dark) dark:text-(--color-light)
                   focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   disabled:opacity-50 disabled:pointer-events-none"
                    placeholder="Confirm password">

                <button type="button" @click="showConfirm = !showConfirm" tabindex="-1"
                    class="absolute inset-y-0 end-4 flex items-center text-(--color-primary)/70 hover:text-(--color-primary) transition cursor-pointer">
                    <i x-show="!showConfirm" data-lucide="eye-off" class="size-5"></i>
                    <i x-show="showConfirm" data-lucide="eye" class="size-5"></i>
                </button>
            </div>

            @isset($user)
                <small class="text-xs text-(--color-dark-gray)">
                    Kosongkan jika tidak ingin mengubah password
                </small>
            @endisset
        </div>
    </div>
    <div class="col-span-1">
        <div class="mb-6 space-y-2">
            <x-select id="roles" name="roles" label="Role" :options="$roles" :value="$user->role_id ?? null"
                placeholder="Choose Role" search-placeholder="Search role..." clearable="true" multiple="true" />
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ $user && $user->is_active == 1 ? 'checked' : '' }}
                class="shrink-0 mt-0.5
                border-(--color-gray) rounded-sm
                text-(--color-primary)
                focus:ring-(--color-primary) checked:border-(--color-primary)
                disabled:opacity-50 cursor-pointer
                dark:bg-(--color-dark-slate)
                dark:border-(--color-slate)
                dark:checked:bg-(--color-primary)
                dark:checked:border-(--color-primary)
                dark:focus:ring-offset-(--color-dark-slate)">

            <label for="is_active" class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) ms-3 cursor-pointer">
                Is Active
            </label>
        </div>
    </div>
</div>
