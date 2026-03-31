@extends('layouts.main', ['title' => 'Image Fabric'])

@push('scripts')
    @vite('resources/js/pages/image-fabric/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Image Fabric</h1>
                <p class="text-sm">Manage image fabric data</p>
            </div>
            <a href="{{ route('image-fabrics.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80">
                <i data-lucide="plus" class="size-4"></i>
                Add Image Fabric
            </a>
        </div>

        <x-cardgrid id="image-fabric-cardgrid" filterId="filter-image-fabric" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>
@endsection
