@extends('layouts.main', ['title' => 'Employee'])

@push('scripts')
    @vite('resources/js/pages/employee/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Employee</h1>
                <p class="text-sm">Manage employee data</p>
            </div>

            <a href="{{ route('employees.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80">
                <i data-lucide="plus" class="size-4"></i>
                Add Employee
            </a>
        </div>

        <x-datatable id="employees-datatable" filterId="filter-employees">
            <thead class="border-b">
                <tr>
                    <th>Name</th>
                    <th>
                        <div class="flex justify-center items-center w-full">
                            Address
                        </div>
                    </th>
                    <th>
                        <div class="flex justify-center items-center w-full">
                            Contact
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
