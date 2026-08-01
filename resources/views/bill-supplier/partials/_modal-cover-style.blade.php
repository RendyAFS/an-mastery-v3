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

        <div class="flex items-center justify-end pt-2">
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
