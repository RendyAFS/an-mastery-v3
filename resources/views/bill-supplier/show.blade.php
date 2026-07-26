@extends('layouts.main', ['title' => 'Bill Supplier - ' . $supplier->name])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/show.js')
@endpush

@section('content')
    <div class="space-y-6 max-w-8xl mx-auto px-4 md:px-0" id="bill-supplier-show" data-supplier-id="{{ $supplier->id }}">

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <a href="{{ route('bill_suppliers.index') }}"
                    class="text-sm text-(--color-primary) flex items-center gap-1 mb-2 hover:underline">
                    <i data-lucide="arrow-left" class="size-4"></i> Kembali ke Supplier
                </a>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $supplier->name }}</h1>
                <p class="text-sm text-(--color-gray) mt-1">Daftar tagihan supplier, dikelompokkan per minggu</p>
            </div>

            @unless ($supplier->trashed())
                <a href="{{ route('bill_suppliers.create', ['supplier_id' => $supplier->id]) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg
                      bg-(--color-primary) text-white font-medium hover:bg-(--color-primary)/80 cursor-pointer whitespace-nowrap">
                    <i data-lucide="plus" class="size-4"></i>
                    Buat Bill
                </a>
            @endunless
        </div>

        <div id="bs-summary" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>

        <div id="bs-loading" class="py-24 text-center text-sm text-(--color-gray) flex flex-col items-center gap-2">
            <i data-lucide="loader-circle" class="size-6 animate-spin"></i>
            Loading data...
        </div>
        <div id="bs-empty" class="py-24 text-center flex flex-col items-center gap-2 text-(--color-gray)">
            <i data-lucide="inbox" class="size-10"></i>
            <p class="text-sm">Belum ada data bill supplier</p>
        </div>
        <div id="bs-weeks" class="hidden space-y-6"></div>
    </div>
@endsection
