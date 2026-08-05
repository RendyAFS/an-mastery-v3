@extends('layouts.main', ['title' => __('sablon.create_title')])

@push('scripts')
    @vite('resources/js/pages/sablon/form.js')
@endpush

@section('content')
    <form id="sablon-form" data-mode="create" class="max-w-6xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray)
               shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            {{-- Header --}}
            <div class="p-4 md:p-5">
                <h3 class="text-2xl font-bold text-(--color-dark) dark:text-(--color-light)">
                    {{ __('sablon.create_title') }}
                </h3>

                <div class="mt-6">
                    @include('sablon.form', ['sablon' => null])
                </div>
            </div>

            {{-- Footer --}}
            <div
                class="bg-(--color-light) shadow-md
                   rounded-b-xl py-3 px-4 md:px-5 flex flex-wrap gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <x-button-loading type="submit" text="{{ __('button-loading.Save') }}"
                    loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-success) hover:bg-(--color-success)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <x-button-loading type="submit" :text="__('button-loading.Save & Create Another')" loadingText="{{ __('button-loading.Saving...') }}"
                    color="bg-(--color-light) hover:bg-(--color-light-gray)"
                    textColor="text-(--color-primary) hover:text-(--color-primary) dark:text-(--color-light) dark:hover:text-(--color-light)"
                    size="py-2 px-4 text-sm" rounded="rounded-lg"
                    class="flex items-center gap-x-2 cursor-pointer
                    border border-(--color-gray) dark:bg-(--color-dark)
                    dark:border-(--color-dark-gray) dark:hover:bg-(--color-dark-slate)"
                    data-action="save-another" />

                <a href="{{ route('sablons.index') }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-danger) hover:bg-(--color-danger)/70 text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    {{ __('button-loading.Cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection
