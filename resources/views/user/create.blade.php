@extends('layouts.main', ['title' => 'Create User'])

@push('scripts')
    @vite('resources/js/pages/user/form.js')
@endpush

@section('content')
    <form id="user-form" data-mode="create" class="max-w-2xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray)
               shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div class="p-4 md:p-5">
                <h3 class="text-lg font-bold text-(--color-dark) dark:text-(--color-light)">
                    Create User
                </h3>

                <div class="mt-4">
                    @include('user.form')
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-(--color-light) shadow-md
                   rounded-b-xl py-3 px-4 md:px-5 flex gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <button type="submit" data-action="save"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-success) text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    Save
                </button>

                <button type="submit" data-action="save-another"
                    class="py-2 px-4 flex items-center gap-x-2 cursor-pointer
                        text-sm font-medium rounded-lg border border-(--color-gray) bg-(--color-light) text-(--color-primary)
                        shadow-2xs hover:bg-(--color-light-gray) focus:outline-none focus:ring-2 focus:ring-(--color-primary)/30
                        disabled:opacity-50 disabled:pointer-events-none transition dark:bg-(--color-dark) dark:border-(--color-slate)
                        dark:text-(--color-light) dark:hover:bg-(--color-dark-slate)">
                    Save & Create Another
                </button>

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
