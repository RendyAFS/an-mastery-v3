@extends('layouts.main', ['title' => 'Sablon'])

@push('scripts')
    @vite('resources/js/pages/sablon/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Sablon</h1>
                <p class="text-sm">Manage sablon data</p>
            </div>

            <div class="flex items-end gap-3">
                <div>
                    <label for="filter-week-sablon"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Week Of
                    </label>
                    <input type="week" id="filter-week-sablon"
                        class="form-input mt-1 px-4 py-2 block w-full rounded-lg
                            bg-(--color-light) border border-(--color-gray)
                            text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                            dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>

                <a href="{{ route('sablons.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i>
                    Add Sablon
                </a>
            </div>
        </div>

        <x-cardgrid id="sablon-cardgrid" filterId="filter-sablon" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>

    @include('sablon.partials._modal-update-status')
@endsection
