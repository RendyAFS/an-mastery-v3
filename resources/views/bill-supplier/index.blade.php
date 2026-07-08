@extends('layouts.main', ['title' => 'Bill Supplier'])

@push('scripts')
    @vite('resources/js/pages/sablon/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Bill Supplier</h1>
                <p class="text-sm">Manage bill supplier data</p>
            </div>
        </div>
    </div>
@endsection
