@extends('layouts.main', ['title' => __('models.Sablon')])

@push('scripts')
    @vite('resources/js/pages/sablon/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Sablon') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('sablon.description') }}</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-week-start"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('sablon.filter.week_start') }}
                    </label>
                    <input type="week" id="filter-week-start"
                        class="form-input mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <div>
                    <label for="filter-week-end"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        {{ __('sablon.filter.week_end') }}
                    </label>
                    <input type="week" id="filter-week-end"
                        class="form-input mt-1 px-4 py-2 block w-48 rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                    text="{{ __('sablon.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-danger) hover:bg-(--color-danger)/80"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('sablons.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i>
                    {{ __('crud.add_title', ['model' => __('models.Sablon')]) }}
                </a>
            </div>
        </div>

        <x-cardgrid id="sablon-cardgrid" filterId="filter-sablon" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>

    @include('sablon.partials._modal-update-status')
@endsection
