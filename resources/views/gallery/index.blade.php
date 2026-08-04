@extends('layouts.main', ['title' => __('models.Gallery')])

@push('scripts')
    @vite('resources/js/pages/gallery/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">{{ __('models.Gallery') }}</h1>
                <p class="text-sm">{{ __('gallery.description') }}</p>
            </div>
            <a href="{{ route('galleries.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                {{ __('crud.add_title', ['model' => __('models.Gallery')]) }}
            </a>
        </div>

        <x-cardgrid id="gallery-cardgrid" filterId="filter-gallery" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>
@endsection
