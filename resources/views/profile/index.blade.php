@extends('layouts.main')

@push('scripts')
    @vite('resources/js/pages/profile/form.js')
@endpush

@section('content')
    <div class="max-w-3xl mx-auto space-y-10">

        {{-- PROFILE --}}
        <form id="profile-form" class="space-y-6 bg-(--color-light) dark:bg-(--color-dark) p-6 rounded-xl">
            <h2 class="text-xl font-bold">Profile Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3 col-span-1">
                    <input type="hidden" id="avatar-preview" value="{{ $user->getFirstMediaUrl('user-profile') ?? '' }}" />
                    <div class="flex justify-center items-center">
                        <img class="w-32 h-32 rounded-full"
                            src="{{ $user->getFirstMediaUrl('user-profile')
                                ? $user->getFirstMediaUrl('user-profile')
                                : 'https://ui-avatars.com/api/?background=random&name=' . urlencode($user->name) }}"
                            alt="{{ $user->name }}" />
                    </div>
                    <input type="hidden" name="avatar_tmp" id="avatar_tmp">
                    <input type="file" name="avatar" class="filepond" accept="image/*" />
                </div>

                <div class="space-y-4 col-span-1 md:col-span-2">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" placeholder="Enter your name"
                            required
                            class="mt-1 w-full rounded-lg px-4 py-2 bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                             focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" placeholder="Enter your email"
                            required
                            class="mt-1 w-full rounded-lg px-4 py-2 bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                             focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30">
                    </div>

                    <x-button-loading type="submit" text="Save Profile" loadingText="Saving..."
                        color="bg-(--color-success) hover:bg-(--color-success)/70"
                        textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                        rounded="rounded-lg" class="cursor-pointer" />
                </div>
            </div>
        </form>

        {{-- PASSWORD --}}
        <form id="password-form" class="space-y-6 bg-(--color-light) dark:bg-(--color-dark) p-6 rounded-xl">
            <h2 class="text-xl font-bold">Change Password</h2>

            <div x-data="{ show: false }" class="space-y-2">
                <label class="block text-sm font-medium">New Password</label>

                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" id="password" autocomplete="new-password"
                        class="peer w-full py-2.5 px-4 pr-12 rounded-lg block
                            bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                            text-(--color-dark) dark:text-(--color-light)
                            focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30"
                        placeholder="Enter password">

                    <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer
                            flex items-center
                            text-(--color-primary)/70 hover:text-(--color-primary)
                            transition">
                        <i x-show="!show" x-cloak data-lucide="eye-off" class="size-5"></i>
                        <i x-show="show" x-cloak data-lucide="eye" class="size-5"></i>
                    </button>
                </div>
            </div>
            <div x-data="{ show: false }" class="space-y-2">
                <label class="block text-sm font-medium">Confirm Password</label>

                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                        autocomplete="new-password"
                        class="peer w-full py-2.5 px-4 pr-12 rounded-lg block
                            bg-(--color-light-gray) dark:bg-(--color-dark-slate)
                            text-(--color-dark) dark:text-(--color-light)
                            focus:border-(--color-gray) focus:ring focus:ring-(--color-gray)/30"
                        placeholder="Confirm password">

                    <button type="button" @click="show = !show" tabindex="-1"
                        class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer
                            flex items-center
                            text-(--color-primary)/70 hover:text-(--color-primary)
                            transition">
                        <i x-show="!show" x-cloak data-lucide="eye-off" class="size-5"></i>
                        <i x-show="show" x-cloak data-lucide="eye" class="size-5"></i>
                    </button>
                </div>
            </div>

            <x-button-loading type="submit" text="Update Password" loadingText="Updating..."
                color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                rounded="rounded-lg" class="cursor-pointer" />
        </form>

    </div>
@endsection
