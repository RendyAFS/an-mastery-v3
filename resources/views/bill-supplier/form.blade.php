@php $isEdit = isset($billSupplier); @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="col-span-1 md:col-span-2">
        @if (!$isEdit)
            <div class="mb-2 space-y-2">
                <label for="sablon_id" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Sablon (Belum Ditagih)
                </label>

                <select id="sablon_id" name="sablon_id" required
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
                    <option value="">Pilih Sablon...</option>
                </select>
                <small class="text-xs text-(--color-dark-gray)">
                    Hanya menampilkan sablon supplier ini yang belum memiliki tagihan
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
        <div id="bs-calc-preview"
            class="{{ $isEdit ? '' : 'hidden' }} p-4 rounded-lg border border-(--color-primary)/30 bg-(--color-primary)/5">
            <p class="text-xs text-(--color-gray) mb-2">Rincian Perhitungan</p>
            <div class="grid grid-cols-3 gap-3 text-sm">
                <div>
                    <p class="text-xs text-(--color-gray)">Total Panjang Kain</p>
                    <p class="font-medium" id="calc-total-long-fabric">
                        {{ $isEdit ? $billSupplier->sablon?->total_long_fabric ?? 0 : 0 }} m
                    </p>
                </div>
                <div>
                    <p class="text-xs text-(--color-gray)">Harga / m</p>
                    <p class="font-medium" id="calc-price">
                        {{ \App\Helpers\RupiahHelper::format($isEdit ? $billSupplier->priceSupplier?->price ?? 0 : 0) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-(--color-gray)">Total Fee</p>
                    <p class="font-bold text-(--color-primary)" id="calc-total-fee">
                        {{ \App\Helpers\RupiahHelper::format($isEdit ? $billSupplier->total_fee ?? 0 : 0) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
