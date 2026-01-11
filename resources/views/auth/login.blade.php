@extends('layouts.auth', ['title' => 'Login'])

@section('content')
    <div class="h-screen">
        <div class="grid lg:grid-cols-5 md:grid-cols-2 items-center gap-y-4 h-full">
            <div class="lg:col-span-2 w-full p-8 max-w-lg max-md:max-w-lg mx-auto">
                <form action="{{ route('login') }}" method="POST">
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
                                for="email">Email / Username</label>
                            <div class="relative">
                                <input type="text" name="email" id="email"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) text-(--color-dark)
                                    border border-(--color-primary) rounded-lg sm:text-sm
                                    focus:border-(--color-primary) focus:ring-(--color-primary) disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    placeholder="Enter Email / Username" required value="{{ old('email') }}"
                                    @error('email') border-red-500 @else border-(--color-primary) @enderror>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                <div
                                    class="absolute inset-y-0 end-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-gray)/70 w-5 h-5"></i>
                                </div>
                            </div>
                        </div>
                        <div x-data="{ show: false }">
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="password">
                                Password
                            </label>

                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                        bg-(--color-light) text-(--color-dark)
                                        border border-(--color-primary) rounded-lg sm:text-sm
                                        focus:border-(--color-primary) focus:ring-(--color-primary)
                                        disabled:opacity-50 disabled:pointer-events-none"
                                    placeholder="Enter Password" required
                                    @error('password') border-red-500 @else border-(--color-primary) @enderror>

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-gray)/70
                                        hover:text-(--color-primary) transition cursor-pointer">
                                    <i x-show="!show" data-lucide="eye-off" class="w-5 h-5"></i>
                                    <i x-show="show" data-lucide="eye" class="w-5 h-5"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex">
                                <input type="checkbox"
                                    class="shrink-0 mt-0.5
                                    border-(--color-primary)rounded-sm
                                    text-(--color-primary) focus:ring-(--color-primary) checked:border-(--color-primary)
                                    disabled:opacity-50 disabled:pointer-events-none
                                    dark:bg-(--color-dark) dark:border-(--color-dark) dark:checked:bg-(--color-primary) dark:checked:border-(--color-primary) dark:focus:ring-offset-(--color-dark)"
                                    name="remember" id="remember">
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
                        <button type="submit"
                            class="w-full py-2.5 px-4 text-[15px]
                            font-medium tracking-wide rounded-md text-(--color-light) hover:text-(--color-dark) transition-colors duration-200 cursor-pointer
                            bg-(--color-primary) hover:bg-(--color-gray) dark:bg-(--color-primary) dark:hover:bg-(--color-gray)">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>

            <div class="hidden md:block max-md:order-1 lg:col-span-3 md:h-screen w-full lg:p-12 p-8 relative overflow-hidden"
                style="background-color: var(--color-dark);">
                <img src="{{ asset('assets/background-auth.webp') }}" class="absolute inset-0 w-full h-full object-cover"
                    alt="login-image" />
            </div>
        </div>
    </div>
@endsection
