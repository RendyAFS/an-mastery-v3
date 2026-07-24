@php $isEdit = isset($billSupplier); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="col-span-1 md:col-span-2">
        @if (!$isEdit)
            <div class="mb-2 space-y-2">
                <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Sablon (Belum Ditagih)
                </label>

                <button type="button" id="btn-select-sablon" data-hs-overlay="#hs-select-sablon-modal"
                    class="w-full flex items-center justify-between px-4 py-2 rounded-lg
                       bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) hover:bg-(--color-gray)/20
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)
                       cursor-pointer">
                    <span id="sablon-selected-summary" class="text-sm text-(--color-gray)">
                        Belum ada sablon dipilih
                    </span>
                    <i data-lucide="chevron-down" class="size-4"></i>
                </button>

                <small class="text-xs text-(--color-dark-gray)">
                    Klik untuk memilih sablon yang akan ditagih. Semua sablon terpilih akan dibuatkan bill sekaligus.
                </small>
            </div>
        @else
            <div class="mb-2 p-4 rounded-lg bg-(--color-light-gray) dark:bg-(--color-dark-slate)">
                <p class="text-xs text-(--color-gray)">Sablon</p>
                <p class="text-sm font-medium">
                    {{ $billSupplier->sablon?->fabric?->name ?? '-' }} ·
                    {{ $billSupplier->sablon?->typeColor?->name ?? '-' }} ·
                    {{ $billSupplier->sablon?->total_long_fabric ?? 0 }} m
                </p>
            </div>
        @endif
    </div>

    <div class="col-span-1">
        <div class="mb-2 space-y-2">
            <label for="date_bill" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Tanggal Bill
            </label>

            <input type="date" id="date_bill" name="date_bill"
                value="{{ $isEdit ? $billSupplier->date_bill?->format('Y-m-d') : now()->format('Y-m-d') }}" required
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" id="is_paid" name="is_paid" value="1"
                {{ $isEdit && $billSupplier->is_paid ? 'checked' : '' }} class="checkbox-custom">

            <label for="is_paid"
                class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) ms-3 cursor-pointer">
                Sudah Dibayar
            </label>
        </div>
    </div>

    <div class="col-span-1">
        <div class="mb-2 space-y-2">
            <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Notes
            </label>

            <textarea id="notes" name="notes" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                   text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                   dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $isEdit ? $billSupplier->notes : '' }}</textarea>
        </div>
    </div>

    <div class="col-span-1 md:col-span-2">
        @if ($isEdit)
            <div id="bs-calc-preview" class="p-4 rounded-lg border border-(--color-primary)/30 bg-(--color-primary)/5">
                <p class="text-xs text-(--color-gray) mb-2">Rincian Perhitungan</p>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-(--color-gray)">Total Panjang Kain</p>
                        <p class="font-medium">{{ $billSupplier->sablon?->total_long_fabric ?? 0 }} m</p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-gray)">Harga / m</p>
                        <p class="font-medium">
                            {{ \App\Helpers\RupiahHelper::format($billSupplier->priceSupplier?->price ?? 0) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-gray)">Total Fee</p>
                        <p class="font-bold text-(--color-primary)">
                            {{ \App\Helpers\RupiahHelper::format($billSupplier->total_fee ?? 0) }}
                        </p>
                    </div>
                </div>
            </div>
        @else
            <div id="bs-calc-preview"
                class="hidden p-4 rounded-lg border border-(--color-primary)/30 bg-(--color-primary)/5">
                <p class="text-xs text-(--color-gray) mb-2">Rincian Perhitungan (Total Gabungan)</p>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-(--color-gray)">Jumlah Sablon</p>
                        <p class="font-medium" id="calc-count">0 sablon</p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-gray)">Total Panjang Kain</p>
                        <p class="font-medium" id="calc-total-long-fabric">0 m</p>
                    </div>
                    <div>
                        <p class="text-xs text-(--color-gray)">Total Fee</p>
                        <p class="font-bold text-(--color-primary)" id="calc-total-fee">Rp0</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@unless ($isEdit)
    {{-- Modal Pilih Sablon --}}
    <div id="hs-select-sablon-modal"
        class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none">
        <div
            class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
            <div
                class="flex flex-col bg-(--color-light) border border-(--color-light-gray) shadow-sm rounded-xl pointer-events-auto
                   dark:bg-(--color-dark) dark:border-(--color-slate) max-h-[90vh]">

                <div class="flex justify-between items-center py-3 px-4 border-b border-(--color-gray)/20">
                    <h3 class="font-bold text-(--color-dark) dark:text-(--color-light)">
                        Pilih Sablon
                    </h3>
                    <button type="button" data-hs-overlay="#hs-select-sablon-modal"
                        class="size-8 inline-flex justify-center items-center rounded-full
                           text-(--color-gray) hover:bg-(--color-gray)/20 cursor-pointer">
                        <i data-lucide="x" class="size-4"></i>
                    </button>
                </div>

                <div class="p-4 overflow-y-auto space-y-3">
                    <div class="flex items-center gap-2 pb-2 border-b border-(--color-gray)/20">
                        <input type="checkbox" id="sablon-check-all" class="checkbox-custom" checked>
                        <label for="sablon-check-all"
                            class="text-sm font-medium text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                            Pilih Semua
                        </label>
                    </div>

                    <div id="sablon-modal-loading" class="text-center text-sm text-(--color-gray) py-6">
                        Loading data...
                    </div>

                    <div id="sablon-modal-empty" class="hidden text-center text-sm text-(--color-gray) py-6">
                        Tidak ada sablon yang belum ditagih
                    </div>

                    <div id="sablon-modal-list" class="hidden space-y-1"></div>
                </div>

                <div class="flex justify-end gap-2 py-3 px-4 border-t border-(--color-gray)/20">
                    <button type="button" data-hs-overlay="#hs-select-sablon-modal"
                        class="px-4 py-2 text-sm font-semibold rounded-lg
                           bg-(--color-light-gray) hover:bg-(--color-gray)/20
                           text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                        Batal
                    </button>
                    <button type="button" id="btn-confirm-sablon"
                        class="px-4 py-2 text-sm font-semibold rounded-lg
                           bg-(--color-primary) hover:bg-(--color-primary)/80 text-white cursor-pointer">
                        Pilih
                    </button>
                </div>
            </div>
        </div>
    </div>
@endunless
