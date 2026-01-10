@extends('layouts.auth', ['title' => 'Register'])

@section('content')
    <div class="h-screen">
        <div class="grid lg:grid-cols-5 md:grid-cols-2 items-center gap-y-4 h-full">
            <div class="lg:col-span-2 w-full p-8 max-w-lg max-md:max-w-lg mx-auto">
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-8">
                        <div class="flex justify-between items-center">
                            <h1 class="text-3xl font-bold text-(--color-dark) dark:text-(--color-light)">
                                Create Account
                            </h1>
                            @include('components.toggle-theme')
                        </div>
                        <p class="text-[15px] mt-6 text-(--color-dark) dark:text-(--color-light)">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium hover:underline ml-1 whitespace-nowrap"
                                style="color: var(--color-primary);">
                                Sign in
                            </a>
                        </p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="name"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Name
                            </label>
                            <div class="relative">
                                <input type="text" name="name" id="name" required
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    placeholder="Enter your name">
                                <div class="absolute inset-y-0 end-4 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="text-(--color-gray)/70 w-5 h-5"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="email"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Email
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" required
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    placeholder="Enter your email">
                                <div class="absolute inset-y-0 end-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-gray)/70 w-5 h-5"></i>
                                </div>
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    placeholder="Create password">

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-gray)/70
                                           hover:text-(--color-primary) transition">
                                    <i x-show="!show" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" required
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    placeholder="Repeat password">

                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-gray)/70
                                           hover:text-(--color-primary) transition">
                                    <i x-show="!show" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <button type="submit"
                            class="w-full py-2.5 px-4 text-[15px]
                                   font-medium tracking-wide rounded-md
                                   text-(--color-light) hover:text-(--color-dark)
                                   transition-colors duration-200
                                   bg-(--color-primary) hover:bg-(--color-gray)">
                            Register
                        </button>
                    </div>
                </form>
            </div>

            <div class="hidden md:block max-md:order-1 lg:col-span-3 md:h-screen w-full
                       md:rounded-tr-xl md:rounded-br-xl
                       relative overflow-hidden"
                style="background-color: var(--color-dark);">
                <img src="{{ asset('assets/background-auth.webp') }}" class="absolute inset-0 w-full h-full object-cover"
                    alt="register-image" />
            </div>
        </div>
    </div>
@endsection
