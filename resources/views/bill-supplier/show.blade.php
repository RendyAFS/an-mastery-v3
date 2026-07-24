@extends('layouts.main', ['title' => 'Bill Supplier - ' . $supplier->name])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/show.js')
@endpush

@section('content')
    <div class="space-y-6" id="bill-supplier-show" data-supplier-id="{{ $supplier->id }}">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('bill_suppliers.index') }}"
                    class="text-sm text-(--color-primary) flex items-center gap-1 mb-1">
                    <i data-lucide="arrow-left" class="size-4"></i> Kembali ke Supplier
                </a>
                <h1 class="text-3xl font-bold">{{ $supplier->name }}</h1>
                <p class="text-sm">Daftar tagihan supplier, dikelompokkan per minggu</p>
            </div>

            @unless ($supplier->trashed())
                <a href="{{ route('bill_suppliers.create', ['supplier_id' => $supplier->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg
                      bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="plus" class="size-4"></i>
                    Buat Bill
                </a>
            @endunless
        </div>

        <div id="bs-loading" class="py-20 text-center text-sm text-(--color-gray)">Loading data...</div>
        <div id="bs-empty" class="hidden py-20 text-center text-sm text-(--color-gray)">Belum ada data bill supplier</div>
        <div id="bs-weeks" class="hidden space-y-6"></div>
    </div>
@endsection
