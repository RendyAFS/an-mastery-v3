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
        <div class="flex justify-between items-center mb-4">
            <div>
                <input id="dt-search" type="text" placeholder="Search..."
                    class="py-2 px-3 text-sm rounded-lg border
                   border-(--color-gray)
                   bg-(--color-light)
                   focus:ring-2 focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate)" />
            </div>

            <div class="flex items-center gap-2">
                <span class="text-sm">Show</span>
                <select id="dt-length" class="py-2 px-3 text-sm rounded-lg border">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>

        <div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-10">
            <div class="overflow-x-auto">
                <table id="users-datatable" class="min-w-full text-sm">
                    <thead class="border-b">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div id="dt-info" class="text-sm"></div>

            <div class="flex items-center gap-1" id="dt-pagination"></div>
        </div>

    </div>
@endsection
