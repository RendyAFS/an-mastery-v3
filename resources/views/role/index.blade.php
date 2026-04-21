@extends('layouts.main')

@push('scripts')
    @vite('resources/js/pages/role/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Roles</h1>
                <p class="text-sm">Manage role data</p>
            </div>

            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80">
                <i data-lucide="plus" class="size-4"></i>
                Add Role
            </a>
        </div>

        <x-datatable id="roles-datatable" filterId="filter-roles">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-start text-xs font-medium text-muted-foreground-1 uppercase">
                        Name</th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-start text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">
                            <i data-lucide="settings" class="size-4"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>
@endsection
