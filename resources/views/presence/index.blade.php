@extends('layouts.main', ['title' => 'Employee Presence'])

@push('scripts')
    @vite('resources/js/pages/presence/index.js')
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold">Employee Presence</h1>
                <p class="text-sm">Manage weekly employee presence data</p>
            </div>

            <div class="flex flex-wrap items-end gap-3">
                <div>
                    <label for="filter-week" class="block text-sm font-medium text-(--color-dark) dark:text-(--color-light)">
                        Week Of
                    </label>
                    <input type="week" id="filter-week" value="2026-W26"
                        class="form-input mt-1 px-4 py-2 block w-56 rounded-lg
                        bg-(--color-light) border border-(--color-gray)
                        text-(--color-dark) focus:border-(--color-primary) focus:ring focus:ring-(--color-primary)/30
                        dark:bg-(--color-dark) dark:border-(--color-slate) dark:text-(--color-light)" />
                </div>
                <button type="button" id="btn-bulk-generate"
                    class="flex items-center gap-2 py-2.5 px-4 rounded-lg bg-(--color-primary) text-white hover:bg-(--color-primary)/80 cursor-pointer">
                    <i data-lucide="calendar-plus" class="size-4"></i>
                    Generate Presence
                </button>
            </div>
        </div>

        <x-datatable id="presences-datatable" filterId="filter-presences" :defaultLength="-1" :filter="false" :lengthOptions="[-1]">
            <thead class="border-b">
                <tr>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        Employee
                    </th>
                    <th id="th-monday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Senin</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-tuesday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Selasa</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-wednesday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Rabu</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-thursday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Kamis</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-friday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Jumat</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-saturday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Sabtu</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th id="th-sunday"
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex flex-col items-center justify-center w-full">
                            <span>Minggu</span>
                            <span class="th-date font-normal text-[10px] opacity-70"></span>
                        </div>
                    </th>
                    <th
                        class="bg-(--color-light-gray) dark:bg-(--color-dark-slate) px-6 py-3 text-xs font-medium text-muted-foreground-1 uppercase">
                        <div class="flex justify-center items-center w-full">Total</div>
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-(--color-gray) dark:divide-(--color-dark-gray)"></tbody>
        </x-datatable>
    </div>

    @include('presence.modal')
    @include('presence.bulk-generate-modal')
@endsection
