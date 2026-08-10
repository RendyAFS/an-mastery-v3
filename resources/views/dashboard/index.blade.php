@extends('layouts.main', ['title' => __('dashboard.title')])

@push('scripts')
    @vite('resources/js/pages/dashboard/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        @include('dashboard._partials._header')
        @include('dashboard._partials._stats')
        @include('dashboard._partials._filter')
        @include('dashboard._partials._charts')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            @include('dashboard._partials._presence')
            @include('dashboard._partials._latest-sablons')
        </div>
        @include('dashboard._partials._fabrics')
    </div>
@endsection
