@extends('layouts.main', ['title' => __('bill-supplier.edit_title')])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/form.js')
@endpush

@section('content')
    <form id="bill-supplier-form" data-mode="edit" data-batch="{{ $batch }}" data-supplier-id="{{ $supplierId }}"
        class="max-w-8xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            <div class="p-4 md:p-5">
                <h3 class="text-2xl font-bold text-(--color-dark) dark:text-(--color-light)">
                    {{ __('bill-supplier.edit_title') }}</h3>

                <div class="mt-6">
                    @include('bill-supplier.form', [
                        'billSuppliers' => $billSuppliers,
                        'dateBill' => $dateBill,
                        'isPaid' => $isPaid,
                        'notes' => $notes,
                    ])
                </div>
            </div>

            <div
                class="bg-(--color-light) shadow-md rounded-b-xl py-3 px-4 md:px-5 flex gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <x-button-loading type="submit" text="{{ __('button-loading.Update') }}"
                    loadingText="{{ __('button-loading.Updating...') }}"
                    color="bg-(--color-success) hover:bg-(--color-success)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('bill_suppliers.by-supplier', $supplierId) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-danger) hover:bg-(--color-danger)/70 text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    {{ __('button-loading.Cancel') }}
                </a>
            </div>
        </div>
    </form>
@endsection
