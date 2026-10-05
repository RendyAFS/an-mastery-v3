@extends('layouts.main', ['title' => __('models.Workshop')])

@push('scripts')
    @vite('resources/js/pages/workshop/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.Workshop') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('workshop.description') }}</p>
            </div>

            <button type="button" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-workshop-modal"
                data-hs-overlay="#hs-workshop-modal" id="btn-create-workshop"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('crud.add_title', ['model' => __('models.Workshop')]) }}
            </button>
        </div>

        <x-cardgrid id="workshop-cardgrid" filterId="filter-workshop" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>

    @include('workshop.modal')
    @include('workshop.partials._modal-preview-image')
@endsection
