@extends('layouts.auth', ['title' => __('auth.register_title')])

@push('scripts')
    @vite('resources/js/pages/auth/auth-form.js')
    @if ($errors->any())
        <script>
            const errors = @json($errors->all());

            errors.forEach((msg, i) => {
                sessionStorage.setItem(
                    "flash_toast_" + i,
                    JSON.stringify({
                        type: "error",
                        title: @json(__('auth.toast.register_failed_title')),
                        message: msg,
                        timeout: 5000
                    })
                );
            });
        </script>
    @endif
@endpush

@section('content')
    <div class="h-screen">
        <div class="grid lg:grid-cols-5 md:grid-cols-2 items-center gap-y-4 h-full">
            <div class="md:col-span-2 w-full p-8 max-w-lg max-md:max-w-lg mx-auto max-h-screen overflow-auto">
                <form action="{{ route('register') }}" method="POST" data-auth-form>
                    @csrf
                    <div class="mb-8">
                        <div class="flex justify-between items-center">
                            <h1 class="text-3xl font-bold text-(--color-dark) dark:text-(--color-light)">
                                {{ __('auth.create_account') }}
                            </h1>
                            <div class="flex items-center gap-4">
                                @include('components.toggle-language')
                                @include('components.toggle-theme')
                            </div>
                        </div>
                        <p class="text-[15px] mt-6 text-(--color-dark) dark:text-(--color-light)">
                            {{ __('auth.have_account') }}
                            <a href="{{ route('login') }}" class="font-medium hover:underline ml-1 whitespace-nowrap"
                                style="color: var(--color-primary);">
                                {{ __('auth.sign_in') }}
                            </a>
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="name">{{ __('auth.fields.name') }}</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" autocomplete="name"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                    border rounded-lg sm:text-sm
                                    {{ $errors->has('name')
                                        ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                        : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)' }}
                                    disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    placeholder="{{ __('auth.placeholders.name') }}" value="{{ old('name') }}">
                                <div
                                    class="absolute inset-y-0 inset-e-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-primary)/80 size-5"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="email">{{ __('auth.fields.email_or_name') }}</label>
                            <div class="relative">
                                <input type="text" name="email" id="email" autocomplete="email"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                    border rounded-lg sm:text-sm
                                    {{ $errors->has('email')
                                        ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                        : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)' }}
                                    disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    placeholder="{{ __('auth.placeholders.email_or_name') }}" value="{{ old('email') }}">
                                <div
                                    class="absolute inset-y-0 inset-e-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-primary)/80 size-5"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                {{ __('auth.fields.password') }}
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                           border rounded-lg sm:text-sm
                                           {{ $errors->has('password')
                                               ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                               : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)' }}"
                                    placeholder="{{ __('auth.placeholders.create_password') }}">

                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute inset-y-0 inset-e-4 flex items-center text-(--color-primary)/80 cursor-pointer">
                                    <i x-show="!show" data-lucide="eye" class="size-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="size-5"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                {{ __('auth.fields.confirm_password') }}
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                           border rounded-lg sm:text-sm
                                           {{ $errors->has('password_confirmation')
                                               ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                               : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)' }}"
                                    placeholder="{{ __('auth.placeholders.repeat_password') }}">

                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute inset-y-0 inset-e-4 flex items-center text-(--color-primary)/80 cursor-pointer">
                                    <i x-show="!show" data-lucide="eye" class="size-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="size-5"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-12">
                        <x-button-loading type="submit" text="{{ __('auth.register') }}"
                            loadingText="{{ __('auth.register_loading') }}"
                            color="bg-(--color-primary) hover:bg-(--color-gray)"
                            textColor="text-(--color-light) hover:text-(--color-dark)" size="w-full py-2.5 px-4 text-[15px]"
                            rounded="rounded-md" class="cursor-pointer" />
                    </div>
                </form>
            </div>

            @include('components.auth-slideshow')
        </div>
    </div>
@endsection
