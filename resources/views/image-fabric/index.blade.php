@extends('layouts.main', ['title' => __('models.ImageFabric')])

@push('scripts')
    @vite('resources/js/pages/image-fabric/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center">
            <div class="mb-3 md:mb-0">
                <h1 class="text-3xl font-bold">{{ __('models.ImageFabric') }}</h1>
                <p class="text-sm text-(--color-dark-gray) mt-1">{{ __('image-fabric.description') }}</p>
            </div>
            <a href="{{ route('image_fabrics.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-(--color-light) hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('crud.add_title', ['model' => __('models.ImageFabric')]) }}
            </a>
        </div>

        <x-cardgrid id="image-fabric-cardgrid" filterId="filter-image-fabric" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>

    @include('image-fabric.partials._modal-preview-image')
@endsection
