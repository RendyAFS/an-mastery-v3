@extends('layouts.main', ['title' => 'Edit User'])

@push('scripts')
    @vite('resources/js/pages/user/form.js')
@endpush

@section('content')
    <form id="user-form" data-mode="edit" data-id="{{ $user->id }}" class="max-w-4xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray)
               shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div class="p-4 md:p-5">
                <h3 class="text-lg font-bold text-(--color-dark) dark:text-(--color-light)">
                    Edit User
                </h3>

                <div class="mt-4">
                    @include('user.form', ['user' => $user])
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-(--color-light) shadow-md
                   rounded-b-xl py-3 px-4 md:px-5 flex gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <x-button-loading type="submit" text="Update" loadingText="Updating..."
                    color="bg-(--color-success) hover:bg-(--color-success)"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-dark-gray) text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
