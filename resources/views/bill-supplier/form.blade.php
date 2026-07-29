@php $isEdit = isset($billSuppliers) && $billSuppliers !== null; @endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    {{-- KIRI: Daftar Sablon --}}
    <div class="lg:col-span-7 space-y-2">
        @if (!$isEdit)
            <div class="flex items-center justify-between gap-2">
                <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Sablon (Belum Ditagih)
                </label>
                <span id="sablon-selected-summary" class="text-xs text-(--color-gray)">
                    Belum ada sablon dipilih
                </span>
            </div>

            <div class="mb-2">
                <input type="text" id="sablon-search" placeholder="Cari fabric..."
                    class="w-full px-4 py-2 rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) text-sm focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>

            <div class="flex items-center gap-2 pb-2 border-b border-(--color-gray)/20">
                <input type="checkbox" id="sablon-check-all" class="checkbox-custom" checked>
                <label for="sablon-check-all"
                    class="text-sm font-medium text-(--color-dark) dark:text-(--color-light) cursor-pointer">
                    Pilih Semua
                </label>
            </div>

            <div class="border border-(--color-gray)/20 rounded-xl p-3 max-h-70vh overflow-y-auto">
                <div id="sablon-modal-loading" class="hidden text-center text-sm text-(--color-gray) py-10">
                    Loading data...
                </div>

                <div id="sablon-modal-empty" class="hidden text-center text-sm text-(--color-gray) py-10">
                    Tidak ada sablon yang belum ditagih
                </div>

                <div id="sablon-modal-list" class="grid grid-cols-1 md:grid-cols-2 gap-3"></div>
            </div>

            <small class="text-xs text-(--color-dark-gray) block">
                Centang sablon yang akan ditagih. Semua sablon terpilih akan dibuatkan bill sekaligus dalam satu
                batch. Hanya sablon berstatus <b>Done</b> yang bisa ditagih.
            </small>
        @else
            <label class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                Sablon dalam Batch Ini
            </label>

            <div class="border border-(--color-gray)/20 rounded-xl p-3 max-h-70vh overflow-y-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($billSuppliers as $bs)
                        @php
                            $sablon = $bs->sablon;
                            $statusColor = match ($sablon?->status?->value) {
                                'ON_PROGRESS' => 'bg-yellow-500/10 text-yellow-600',
                                'DONE' => 'bg-blue-500/10 text-blue-600',
                                'DELIVERED' => 'bg-green-500/10 text-green-600',
                                'RETURNED' => 'bg-red-500/10 text-red-600',
                                default => 'bg-gray-500/10 text-gray-600',
                            };
                        @endphp

                        <div
                            class="p-3 rounded-lg border border-(--color-gray)/10 bg-(--color-light-gray) dark:bg-(--color-dark-slate) space-y-2">
                            {{-- Header --}}
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm truncate">
                                        {{ $sablon?->imageFabric?->name ?? '-' }} |
                                        {{ $sablon?->typeFabric?->name ?? '-' }}
                                    </p>
                                    <p class="text-xs text-(--color-dark-gray)">
                                        {{ $sablon?->date_sablon?->translatedFormat('d F Y') ?? '-' }}
                                    </p>
                                </div>
                                <span
                                    class="shrink-0 text-[11px] px-2 py-0.5 rounded-full font-medium {{ $statusColor }}">
                                    {{ $sablon?->status?->labels() ?? '-' }}
                                </span>
                            </div>

                            {{-- Summary --}}
                            <div class="grid grid-cols-2 gap-2">
                                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Long Fabric</p>
                                    <p class="text-sm font-medium">{{ $sablon?->total_long_fabric ?? 0 }} m</p>
                                </div>
                                <div class="bg-(--color-gray)/10 rounded-lg p-2">
                                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Type Color</p>
                                    <p class="text-sm font-medium">{{ $sablon?->typeColor?->name ?? '-' }} Warna</p>
                                </div>
                                <div class="bg-(--color-gray)/10 rounded-lg p-2 col-span-2">
                                    <p class="text-[11px] text-(--color-dark-gray) mb-0.5">Total Fee</p>
                                    <p class="text-sm font-semibold text-(--color-primary)">
                                        {{ \App\Helpers\RupiahHelper::format($bs->total_fee) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Fabric Details --}}
                            @if ($sablon?->sablonDetails?->isNotEmpty())
                                <div class="space-y-1">
                                    <p class="text-xs font-semibold">Fabric Details</p>
                                    <ul class="space-y-1 text-xs">
                                        @foreach ($sablon->sablonDetails as $detail)
                                            <li class="flex justify-between">
                                                <span>• {{ $detail->colorFabric?->name ?? '-' }}</span>
                                                <span class="font-medium">{{ $detail->long_fabric ?? 0 }} m</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <small class="text-xs text-(--color-dark-gray) block">
                Sablon dalam satu batch tidak dapat diubah setelah dibuat.
            </small>
        @endif
    </div>

    {{-- KANAN: Input & Summary --}}
    <div class="lg:col-span-5 space-y-4">
        <div>
            <div class="mb-2 space-y-2">
                <label for="date_bill" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Tanggal Bill
                </label>

                <input type="date" id="date_bill" name="date_bill"
                    value="{{ $isEdit ? $dateBill?->format('Y-m-d') : now()->format('Y-m-d') }}" required
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" id="is_paid" name="is_paid" value="1"
                    {{ $isEdit && $isPaid ? 'checked' : '' }} class="checkbox-custom">

                <label for="is_paid"
                    class="text-sm font-semibold text-(--color-dark) dark:text-(--color-light) ms-3 cursor-pointer">
                    Sudah Dibayar
                </label>
            </div>

            <div class="mb-2 space-y-2">
                <label for="notes" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                    Notes
                </label>

                <textarea id="notes" name="notes" rows="3"
                    class="mt-1 px-4 py-2 block w-full rounded-lg bg-(--color-light-gray) border border-(--color-gray)
                       text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                       dark:bg-(--color-dark-slate) dark:border-(--color-slate) dark:text-(--color-light)">{{ $isEdit ? $notes : '' }}</textarea>
            </div>
        </div>

        <div id="bs-calc-preview"
            class="{{ $isEdit ? '' : 'hidden' }} p-4 rounded-lg border border-(--color-primary)/30 bg-(--color-primary)/5 space-y-3 sticky top-4">
            <div>
                <p class="text-xs text-(--color-gray) mb-2">Rincian Perhitungan (Total Batch)</p>
                <div class="grid grid-cols-3 gap-3 text-sm">
                    <div>
                        <p class="text-xs text-(--color-gray)">Jumlah Sablon</p>
                        <p class="font-medium" id="calc-count">
                            {{ $isEdit ? count($billSuppliers) . ' sablon' : '0 sablon' }}
                        </p>
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
            <div id="calc-item-list" class="space-y-1 border-t border-(--color-gray)/20 pt-2 max-h-64 overflow-y-auto">
            </div>
        </div>
    </div>
</div>
