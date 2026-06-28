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
            <a href="{{ route('sablons.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                  bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                <i data-lucide="plus" class="size-4"></i>
                Add Sablon
            </a>
        </div>

        <x-cardgrid id="sablon-cardgrid" filterId="filter-sablon" :defaultLength="12" :lengthOptions="[12, 24, 48]" />
    </div>
@endsection
