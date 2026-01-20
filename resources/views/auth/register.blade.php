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
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="name">Name</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" autocomplete="name"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) text-(--color-dark)
                                    border border-(--color-primary) rounded-lg sm:text-sm
                                    focus:border-(--color-primary) focus:ring-(--color-primary) disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    @error('name') class="border-(--color-red)" @enderror placeholder="Enter Name"
                                    value="{{ old('name') }}">
                                <div
                                    class="absolute inset-y-0 end-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-primary)/80 w-5 h-5"></i>
                                </div>
                            </div>
                            @error('name')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)"
                                for="email">Email / Name</label>
                            <div class="relative">
                                <input type="text" name="email" id="email" autocomplete="email"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) text-(--color-dark)
                                    border border-(--color-primary) rounded-lg sm:text-sm
                                    focus:border-(--color-primary) focus:ring-(--color-primary) disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    @error('email') class="border-(--color-red)" @enderror placeholder="Enter Email / Name"
                                    value="{{ old('email') }}">
                                <div
                                    class="absolute inset-y-0 end-4 flex items-center pointer-events-none peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                                    <i data-lucide="mail" class="text-(--color-primary)/80 w-5 h-5"></i>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    @error('password') class="border-(--color-red)" @enderror placeholder="Create password">

                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-primary)/80">
                                    <i x-show="!show" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation"
                                class="text-[15px] font-medium mb-2 block text-(--color-dark) dark:text-(--color-light)">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) text-(--color-dark)
                                           border border-(--color-primary) rounded-lg sm:text-sm
                                           focus:border-(--color-primary) focus:ring-(--color-primary)"
                                    @error('password_confirmation') class="border-(--color-red)" @enderror
                                    placeholder="Repeat password">

                                <button type="button" @click="show = !show" tabindex="-1"
                                    class="absolute inset-y-0 end-4 flex items-center text-(--color-primary)/80">
                                    <i x-show="!show" data-lucide="eye" class="w-5 h-5"></i>
                                    <i x-show="show" data-lucide="eye-off" class="w-5 h-5"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <p class="mt-2 text-sm text-(--color-red)">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-12">
                        <x-button-loading type="submit" text="Register" loadingText="Registering..."
                            color="bg-(--color-primary) hover:bg-(--color-gray)"
                            textColor="text-(--color-light) hover:text-(--color-dark)" size="w-full py-2.5 px-4 text-[15px]"
                            rounded="rounded-md" class="cursor-pointer" />
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
