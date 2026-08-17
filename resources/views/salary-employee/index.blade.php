@extends('layouts.main', ['title' => __('models.SalaryEmployee')])

@push('scripts')
    @vite('resources/js/pages/salary-employee/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.SalaryEmployee') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('salary-employee.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-date-range"
                        class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('salary-employee.filter.date_range') }}
                    </label>
                    <x-datepicker id="filter-date-range" name="date_range" mode="range" clearable="true"
                        bgClass="bg-(--color-light) dark:bg-(--color-dark)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('salary-employee.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <x-button-loading type="button" id="btn-sync-salary" icon="refresh-cw"
                    text="{{ __('salary-employee.sync.button') }}" loadingText="{{ __('salary-employee.sync.loading') }}"
                    color="bg-(--color-primary) hover:bg-(--color-primary)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />
            </div>
        </div>

        <x-cardgrid id="salary-employee-cardgrid" filterId="filter-salary-employee" :defaultLength="48" :lengthOptions="[12, 24, 48]"
            :filterOptions="[
                'PENDING' => __('enums.status_salary_employee.PENDING'),
                'PAID' => __('enums.status_salary_employee.PAID'),
                'all' => __('salary-employee.filter.all'),
            ]" filterDefault="all" />
    </div>

    @include('salary-employee.partials._modal-salary-employee')
@endsection
