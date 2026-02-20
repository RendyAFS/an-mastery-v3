@extends('layouts.auth', ['title' => 'Login'])

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
                        title: "Login Failed",
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
                <form action="{{ route('login') }}" method="POST" data-auth-form>
                    @csrf
                    <div class="mb-8">
                        <div class="flex justify-between items-center">
                            <h1 class="text-3xl font-bold text-(--color-dark) dark:text-(--color-light)">Sign in</h1>
                            @include('components.toggle-theme')
                        </div>
                        <p class="text-[15px] mt-6 text-(--color-dark) dark:text-(--color-light)">Don't have an account
                            <a href="{{ route('register') }}" class="font-medium hover:underline ml-1 whitespace-nowrap"
                                style="color: var(--color-primary);">Register here</a>
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="email">Email / Name</label>
                            <div class="relative">
                                <input type="text" name="email" id="email" autocomplete="email"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                    border border-(--color-primary) rounded-lg sm:text-sm
                                    focus:border-(--color-primary) focus:ring-(--color-primary) disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)
                                    @error('email') border-(--color-red) @enderror"
                                    placeholder="Enter Email / Name" value="{{ old('email') }}">
                                <div
                                    class="absolute inset-y-0 end-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-primary)/80 size-5"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>
                        <div x-data="{ show: false }">
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="password">
                                Password
                            </label>

                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    autocomplete="password"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                        bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                        border border-(--color-primary) rounded-lg sm:text-sm
                                        focus:border-(--color-primary) focus:ring-(--color-primary)
                                        disabled:opacity-50 disabled:pointer-events-none
                                    @error('password') border-(--color-red) @enderror"
                                    placeholder="Enter Password">

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-primary)/70
                                        hover:text-(--color-primary) transition cursor-pointer">
                                    <i x-show="!show" data-lucide="eye-off" class="size-5"></i>
                                    <i x-show="show" data-lucide="eye" class="size-5"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex">
                                <input type="checkbox" name="remember" id="remember" value="1"
                                    class="checkbox-custom">
                                <label for="remember"
                                    class="text-sm text-(--color-dark) ms-3 dark:text-(--color-light)">Remember me</label>
                            </div>
                            <div>
                                <a href="{{ route('password.request') }}" class="font-medium text-sm hover:underline"
                                    style="color: var(--color-primary);">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <x-button-loading type="submit" text="Sign in" loadingText="Signing in..."
                            color="bg-(--color-primary) hover:bg-(--color-gray)"
                            textColor="text-(--color-light) hover:text-(--color-dark)" size="w-full py-2.5 px-4 text-[15px]"
                            rounded="rounded-md" class="cursor-pointer" />
                    </div>
                </form>
            </div>

            <div class="hidden lg:block max-lg:order-1 md:col-span-3 lg:h-screen w-full relative overflow-hidden"
                style="background-color: var(--color-dark);">
                <img src="{{ asset('assets/background-auth.webp') }}" class="absolute inset-0 w-full h-full object-cover"
                    alt="login-image" />
            </div>
        </div>
    </div>
@endsection
