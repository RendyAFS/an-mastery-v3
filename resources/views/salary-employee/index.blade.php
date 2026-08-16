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
                    <label for="filter-week-start"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('salary-employee.filter.week_start') }}
                    </label>
                    <input type="week" id="filter-week-start"
                        class="mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <div>
                    <label for="filter-week-end"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('salary-employee.filter.week_end') }}
                    </label>
                    <input type="week" id="filter-week-end"
                        class="mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('salary-employee.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer mt-6" />

                <x-button-loading type="button" id="btn-sync-salary" icon="refresh-cw"
                    text="{{ __('salary-employee.sync.button') }}" loadingText="{{ __('salary-employee.sync.loading') }}"
                    color="bg-(--color-primary) hover:bg-(--color-primary)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer mt-6" />
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
