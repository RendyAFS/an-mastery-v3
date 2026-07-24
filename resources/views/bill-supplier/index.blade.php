@extends('layouts.main', ['title' => 'Bill Supplier'])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/list.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Bill Supplier</h1>
                <p class="text-sm">Pilih supplier untuk melihat data tagihan</p>
            </div>
        </div>

        <x-cardgrid id="bill-supplier-cardgrid" :filter="false" :lengthOptions="[12, 24, 48]" :defaultLength="12" />
    </div>
@endsection
