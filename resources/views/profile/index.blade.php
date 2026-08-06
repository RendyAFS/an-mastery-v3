@extends('layouts.main')

@push('scripts')
    @vite('resources/js/pages/profile/form.js')
@endpush

@section('content')
    <div class="max-w-5xl mx-auto space-y-10">

        {{-- PROFILE --}}
        <form id="profile-form" class="space-y-6 bg-(--color-light) dark:bg-(--color-dark) p-6 rounded-xl">
            <h2 class="text-xl font-bold">{{ __('my-profile.title.profile_information') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3 col-span-1">
                    <input type="hidden" id="avatar-preview" value="{{ $user->getFirstMediaUrl('user-profile') ?? '' }}" />

                    <div class="space-y-3 col-span-1">
                        <input type="hidden" id="avatar-preview"
                            value="{{ $user->getFirstMediaUrl('user-profile') ?? '' }}" />

                        <input type="hidden" id="avatar-path"
                            value="{{ optional($user->getFirstMedia('user-profile'))->getPath() }}" />

                        <input type="hidden" name="avatar_tmp" id="avatar_tmp">
                        <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">

                        <input type="file" name="avatar" class="filepond" accept="image/*" />
                        <button type="button" id="camera-btn"
                            class="py-2 px-4 text-sm flex items-center rounded-lg cursor-pointer border border-(--color-gray) bg-(--color-light) hover:bg-(--color-light-gray) text-(--color-primary) hover:text-(--color-primary) dark:bg-(--color-dark) dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate) dark:text-(--color-light) dark:hover:text-(--color-light) transition-colors duration-200">
                            <i data-lucide="camera" class="w-4 h-4 mr-2"></i>{{ __('filepond.take_photo') }}
                        </button>
                    </div>
                </div>

                <div class="space-y-4 col-span-1 md:col-span-2">

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">
                            {{ __('my-profile.form.name') }}
                        </label>

                        <input type="text" name="name" value="{{ $user->name }}"
                            placeholder="{{ __('my-profile.form.placeholder_name') }}"
                            class="mt-1 w-full rounded-lg px-4 py-2
                                bg-(--color-light-gray)
                                dark:bg-(--color-dark-slate)
                                focus:border-(--color-gray)
                                focus:ring
                                focus:ring-(--color-gray)/30">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">
                            {{ __('my-profile.form.email') }}
                        </label>

                        <input type="email" name="email" value="{{ $user->email }}"
                            placeholder="{{ __('my-profile.form.placeholder_email') }}"
                            class="mt-1 w-full rounded-lg px-4 py-2
                                bg-(--color-light-gray)
                                dark:bg-(--color-dark-slate)
                                focus:border-(--color-gray)
                                focus:ring
                                focus:ring-(--color-gray)/30">
                    </div>

                    <x-button-loading type="submit" :text="__('my-profile.button.save_profile')" :loadingText="__('my-profile.button.saving_profile')"
                        color="bg-(--color-success) hover:bg-(--color-success)/70"
                        textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                        rounded="rounded-lg" class="cursor-pointer" />

                </div>
            </div>
        </form>

        {{-- PASSWORD --}}
        <form id="password-form" class="space-y-6 bg-(--color-light) dark:bg-(--color-dark) p-6 rounded-xl">

            <input type="email" name="username" value="{{ $user->email }}" autocomplete="username" hidden>

            <h2 class="text-xl font-bold">
                {{ __('my-profile.title.change_password') }}
            </h2>

            <div x-data="{ show: false }" class="space-y-2">

                <label class="block text-sm font-medium">
                    {{ __('my-profile.form.password') }}
                </label>

                <div class="relative">

                    <input :type="show ? 'text' : 'password'" name="password" id="password" autocomplete="new-password"
                        placeholder="{{ __('my-profile.form.placeholder_password') }}"
                        class="peer w-full py-2.5 px-4 pr-12 rounded-lg block
                            bg-(--color-light-gray)
                            dark:bg-(--color-dark-slate)
                            text-(--color-dark)
                            dark:text-(--color-light)
                            focus:border-(--color-gray)
                            focus:ring
                            focus:ring-(--color-gray)/30">

                    <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-4 top-1/2 -translate-y-1/2
                            flex items-center
                            cursor-pointer
                            text-(--color-primary)/70
                            hover:text-(--color-primary)
                            transition">

                        <i x-show="!show" x-cloak data-lucide="eye-off" class="size-5"></i>
                        <i x-show="show" x-cloak data-lucide="eye" class="size-5"></i>

                    </button>

                </div>
            </div>

            <div x-data="{ show: false }" class="space-y-2">

                <label class="block text-sm font-medium">
                    {{ __('my-profile.form.password_confirmation') }}
                </label>

                <div class="relative">

                    <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                        autocomplete="new-password"
                        placeholder="{{ __('my-profile.form.placeholder_password_confirmation') }}"
                        class="peer w-full py-2.5 px-4 pr-12 rounded-lg block
                            bg-(--color-light-gray)
                            dark:bg-(--color-dark-slate)
                            text-(--color-dark)
                            dark:text-(--color-light)
                            focus:border-(--color-gray)
                            focus:ring
                            focus:ring-(--color-gray)/30">

                    <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-4 top-1/2 -translate-y-1/2
                            flex items-center
                            cursor-pointer
                            text-(--color-primary)/70
                            hover:text-(--color-primary)
                            transition">

                        <i x-show="!show" x-cloak data-lucide="eye-off" class="size-5"></i>
                        <i x-show="show" x-cloak data-lucide="eye" class="size-5"></i>

                    </button>

                </div>
            </div>

            <x-button-loading type="submit" :text="__('my-profile.button.update_password')" :loadingText="__('my-profile.button.updating_password')"
                color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                rounded="rounded-lg" class="cursor-pointer" />

        </form>

    </div>
@endsection
