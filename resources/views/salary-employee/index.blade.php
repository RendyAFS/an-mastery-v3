@extends('layouts.main', ['title' => 'Salary Employee'])

@push('scripts')
    @vite('resources/js/pages/salary-employee/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">Salary Employee</h1>
                <p class="text-sm">Rekap fee karyawan per minggu</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div>
                    <label for="filter-week-salary"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Week Of
                    </label>
                    <input type="week" id="filter-week-salary"
                        class="form-input mt-1 px-4 py-2 block w-56 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <x-button-loading type="button" id="btn-sync-salary" icon="refresh-cw" text="Sync"
                    loadingText="Syncing..." color="bg-(--color-primary) hover:bg-(--color-primary)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer mt-6" />
            </div>
        </div>

        <x-cardgrid id="salary-employee-cardgrid" filterId="filter-salary-employee" :defaultLength="12" :lengthOptions="[12, 24, 48]"
            :filterOptions="[
                'PENDING' => 'Pending',
                'PAID' => 'Paid',
                'all' => 'All',
            ]" filterDefault="all" />
    </div>

    @include('salary-employee.partials._modal-salary-employee')
@endsection
