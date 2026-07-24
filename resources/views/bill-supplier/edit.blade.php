@extends('layouts.main', ['title' => 'Edit Bill Supplier'])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/form.js')
@endpush

@section('content')
    <form id="bill-supplier-form" data-mode="edit" data-id="{{ $billSupplier->id }}"
        data-supplier-id="{{ $billSupplier->supplier_id }}" class="max-w-4xl mx-auto">
        <div
            class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-2xs rounded-xl
               dark:bg-(--color-dark) dark:border-(--color-slate)">

            <div class="p-4 md:p-5">
                <h3 class="text-2xl font-bold text-(--color-dark) dark:text-(--color-light)">Edit Bill Supplier</h3>

                <div class="mt-6">
                    @include('bill-supplier.form', ['billSupplier' => $billSupplier])
                </div>
            </div>

            <div
                class="bg-(--color-light) shadow-md rounded-b-xl py-3 px-4 md:px-5 flex gap-2
                   dark:bg-(--color-dark) dark:border-(--color-slate)">

                <x-button-loading type="submit" text="Update" loadingText="Updating..."
                    color="bg-(--color-success) hover:bg-(--color-success)/70"
                    textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                    rounded="rounded-lg" class="cursor-pointer" />

                <a href="{{ route('bill_suppliers.by-supplier', $billSupplier->supplier_id) }}"
                    class="px-4 py-2 text-sm font-semibold rounded-lg
                       bg-(--color-danger) hover:bg-(--color-danger)/70 text-(--color-light) cursor-pointer
                       hover:opacity-90 transition">
                    Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
