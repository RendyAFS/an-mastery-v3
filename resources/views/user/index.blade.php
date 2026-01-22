@extends('layouts.main', ['title' => 'User'])

@push('scripts')
    @vite('resources/js/pages/user/list.js')
@endpush

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-4 py-4 items-center">
        <div class="mb-4 sm:mb-0 sm:col-span-3 flex flex-col">
            <span class="text-3xl font-bold">Users</span>
            <span class="text-sm">Manage user data</span>
        </div>
        <div class="sm:col-span-1 flex justify-end">
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center transition duration-300 ease-in-out
                    gap-x-2 px-4 py-2 rounded-lg text-(--color-light)
                    bg-(--color-primary) hover:bg-(--color-primary)/70">
                <i data-lucide="plus" class="size-4"></i>
                Add User
            </a>
        </div>
    </div>
@endsection
