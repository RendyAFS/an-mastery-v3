@props([
    'id' => 'datatable',
    'search' => true,
    'length' => true,
    'lengthOptions' => [10, 20, 50],
    'defaultLength' => 10,
])

{{-- Top Bar --}}
<div class="flex flex-col gap-3 mb-4 sm:flex-row sm:justify-between sm:items-center">
    <div>
        @if ($search)
            <div class="relative w-full sm:w-auto">
                <input type="text" id="dt-search" name="dt-search"
                    class="w-full sm:w-64
                    ps-10 py-2 px-3 text-sm rounded-lg
                    text-(--color-dark) dark:text-(--color-light)
                    border border-(--color-gray) dark:border-(--color-dark-gray)
                    bg-(--color-light) dark:bg-(--color-dark-slate)
                    focus:ring-2 focus:ring-(--color-primary)/30"
                    placeholder="Search...">
                <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-4">
                    <i data-lucide="search" class="size-4"></i>
                </div>
            </div>
        @endif
    </div>

    @if ($length)
        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            <span class="text-sm whitespace-nowrap">Show</span>
            <select id="dt-length" class="hidden w-full sm:w-28"
                data-hs-select='{
                            "placeholder": "Show",
                            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                            "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-2 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-(--color-gray)",
                            "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-(--color-light) dark:bg-(--color-dark-slate) border border-(--color-gray) dark:border-(--color-dark-gray) rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-(--color-gray) [&::-webkit-scrollbar-thumb]:bg-(--color-gray)",
                            "optionClasses": "ps-3 py-2 px-4 w-full text-sm text-(--color-dark) dark:text-(--color-light) cursor-pointer hover:bg-(--color-dark-gray)/50 rounded-lg focus:outline-hidden focus:bg-(--color-gray) hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50",
                            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-(--color-dark) dark:text-(--color-light) \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                            "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"size-3.5 text-gray-500 dark:text-gray-400\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                        }'
                class="hidden py-2 px-3 text-sm rounded-lg border">
                @foreach ($lengthOptions as $opt)
                    <option value="{{ $opt }}" @selected($opt == $defaultLength)>
                        {{ $opt }}
                    </option>
                @endforeach
            </select>
        </div>
    @endif
</div>

{{-- Table --}}
<div class="bg-(--color-light) dark:bg-(--color-dark) rounded-xl shadow p-10">
    <div class="overflow-x-auto">
        <table id="{{ $id }}" class="min-w-full text-sm">
            {{ $slot }}
        </table>
    </div>
</div>

{{-- Footer --}}
<div class="flex flex-col gap-3 mt-4 sm:flex-row sm:justify-between sm:items-center">
    <div id="dt-info" class="text-sm text-center sm:text-left"></div>
    <div id="dt-pagination" class="flex flex-wrap justify-center gap-1 sm:justify-end"></div>
</div>
