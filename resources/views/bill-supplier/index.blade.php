@extends('layouts.main', ['title' => 'Bill Supplier'])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/list.js')
@endpush

@section('content')
    <div class="space-y-6 max-w-8xl mx-auto px-4 md:px-0">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Bill Supplier</h1>
                <p class="text-sm text-(--color-gray) mt-1">Pilih supplier untuk melihat data tagihan</p>
            </div>
        </div>

        <div
            class="flex flex-wrap justify-between items-center gap-4 bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-4">
            <div class="font-bold text-(--color-dark) dark:text-(--color-light)">Filter berdasarkan minggu</div>
            <div class="flex flex-wrap items-end gap-4">
                <div class="w-full sm:w-56">
                    <label for="filter-week-start"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Start Week
                    </label>

                    <input type="week" id="filter-week-start" class="form-input mt-1 w-full" />
                </div>

                <div class="w-full sm:w-56">
                    <label for="filter-week-end"
                        class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        End Week
                    </label>

                    <input type="week" id="filter-week-end" class="form-input mt-1 w-full" />
                </div>

                <button type="button" id="filter-week-reset"
                    class="h-[42px] inline-flex items-center gap-2 px-4 rounded-lg text-sm
                bg-(--color-primary) text-white font-medium hover:bg-(--color-primary)/80 cursor-pointer whitespace-nowrap">
                    <i data-lucide="rotate-ccw" class="size-4"></i>
                    Reset ke Minggu Ini
                </button>
            </div>
        </div>
        <x-cardgrid id="bill-supplier-cardgrid" :filter="false" :lengthOptions="[12, 24, 48]" :defaultLength="12" />
    </div>

    <div id="cover-style-modal" class="hidden fixed inset-0 z-9999 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>

        <div
            class="relative z-9999 bg-(--color-light) dark:bg-(--color-dark) rounded-2xl shadow-xl w-full max-w-md p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold">Atur Tampilan Cover</h3>
                <button type="button" data-modal-close class="p-1.5 rounded-lg hover:bg-(--color-gray)/20 cursor-pointer">
                    <i data-lucide="x" class="size-5"></i>
                </button>
            </div>

            <div id="cover-style-preview" class="relative rounded-xl px-4 pt-4 pb-6 overflow-hidden"></div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-medium text-(--color-gray)">Warna Awal</label>
                    <input type="color" id="cs-color-from"
                        class="w-full h-10 rounded-lg cursor-pointer border border-(--color-gray)/20">
                </div>
                <div>
                    <label class="text-xs font-medium text-(--color-gray)">Warna Akhir</label>
                    <input type="color" id="cs-color-to"
                        class="w-full h-10 rounded-lg cursor-pointer border border-(--color-gray)/20">
                </div>
            </div>

            <div class="flex flex-wrap gap-2" id="cover-style-palette"></div>

            <x-select name="cs_icon" id="cs-icon" label="Icon" placeholder="Pilih icon" searchPlaceholder="Cari icon..."
                :options="$iconOptions" :dropdownMaxH="'max-h-40'" />

            <x-select name="cs_pattern" id="cs-pattern" label="Pattern" placeholder="Pilih pattern"
                searchPlaceholder="Cari pattern..." :options="$patternOptions" :dropdownMaxH="'max-h-40'" />

            <div class="flex items-center justify-between pt-2">
                <button type="button" id="cs-reset" class="text-sm text-(--color-red) hover:underline cursor-pointer">
                    Reset ke Default
                </button>
                <div class="flex gap-2">
                    <button type="button" data-modal-close
                        class="px-4 py-2 rounded-lg text-sm border border-(--color-gray)/20 cursor-pointer">
                        Batal
                    </button>
                    <button type="button" id="cs-save"
                        class="px-4 py-2 rounded-lg text-sm bg-(--color-primary) text-white cursor-pointer">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
