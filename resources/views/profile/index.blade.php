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
                    <label class="block text-sm font-medium">Profile Image</label>
                    <div class="flex flex-col space-y-4">
                        <img src="{{ $user->getFirstMediaUrl('user-profile') ?: 'https://ui-avatars.com/api/?name=' . $user->name }}"
                            class="size-26 rounded-full object-cover border" id="avatar-preview">

                        <div class="space-y-2">
                            <input type="file" name="avatar" accept="image/*" class="block text-sm">

                            @if ($user->getFirstMedia('user-profile'))
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" name="remove_avatar" value="1"
                                        class="shrink-0 mt-0.5
                                        border-(--color-danger) rounded-sm
                                        text-(--color-danger) focus:ring-(--color-danger) checked:border-(--color-danger)
                                        disabled:opacity-50 disabled:pointer-events-none
                                        dark:bg-(--color-dark-slate) dark:border-(--color-danger)
                                        dark:checked:bg-(--color-danger) dark:checked:border-(--color-danger)
                                        dark:focus:ring-offset-(--color-dark)">
                                    <span class="text-(--color-danger) font-semibold">
                                        Remove image
                                    </span>
                                </label>
                            @endif
                        </div>
                    </div>
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
