@extends('layouts.auth', ['title' => 'Register'])

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
                        title: "Register Failed",
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
                                    bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                    border rounded-lg sm:text-sm
                                    {{ $errors->has('name')
                                        ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                        : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)'
                                    }}
                                    disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    placeholder="Enter Name" value="{{ old('name') }}">
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
                                for="email">Email / Name</label>
                            <div class="relative">
                                <input type="text" name="email" id="email" autocomplete="email"
                                    class="peer py-2.5 sm:py-3 px-4 ps-4 block w-full
                                    bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                    border rounded-lg sm:text-sm
                                    {{ $errors->has('email')
                                        ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                        : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)'
                                    }}
                                    disabled:opacity-50 disabled:pointer-events-none
                                    placeholder-(--color-gray)"
                                    placeholder="Enter Email / Name" value="{{ old('email') }}">
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
                                Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password" id="password"
                                    autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                           border rounded-lg sm:text-sm
                                           {{ $errors->has('password')
                                               ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                               : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)'
                                           }}"
                                    placeholder="Create password">

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
                                Confirm Password
                            </label>
                            <div class="relative">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" autocomplete="false"
                                    class="peer py-2.5 sm:py-3 px-4 block w-full
                                           bg-(--color-light) dark:bg-(--color-dark) text-(--color-dark) dark:text-(--color-light)
                                           border rounded-lg sm:text-sm
                                           {{ $errors->has('password_confirmation')
                                               ? 'border-(--color-red) focus:border-(--color-red) focus:ring-(--color-red)'
                                               : 'border-(--color-primary) focus:border-(--color-primary) focus:ring-(--color-primary)'
                                           }}"
                                    placeholder="Repeat password">

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
                        <x-button-loading type="submit" text="Register" loadingText="Registering..."
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
