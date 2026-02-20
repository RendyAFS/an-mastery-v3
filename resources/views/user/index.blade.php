@extends('layouts.main', ['title' => 'Users'])

@push('scripts')
    @vite('resources/js/pages/user/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Users</h1>
                <p class="text-sm">Manage user data</p>
            </div>

            <a href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80">
                <i data-lucide="plus" class="size-4"></i>
                Add User
            </a>
        </div>

        <x-datatable id="users-datatable" filterId="filter-users">
            <thead class="border-b">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>
                        <div class="flex justify-center items-center w-full">
                            Roles
                        </div>
                    </th>
                    <th>
                        <div class="flex justify-center items-center w-full">
                            Is Active
                        </div>
                    </th>
                    <th>
                        <div class="flex justify-center items-center w-full">
                            <i data-lucide="settings" class="size-4"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody></tbody>
        </x-datatable>
    </div>
@endsection
