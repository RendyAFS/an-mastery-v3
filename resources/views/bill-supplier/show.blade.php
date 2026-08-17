@extends('layouts.main', ['title' => __('models.BillSupplier') . ' - ' . $supplier->name])

@push('scripts')
    @vite('resources/js/pages/bill-supplier/show.js')
@endpush

@section('content')
    <div class="space-y-6" id="bill-supplier-show" data-supplier-id="{{ $supplier->id }}"
        data-week-start="{{ request('week_start') }}" data-week-end="{{ request('week_end') }}">

        @php
            $patternSvgMap = [
                'dots' =>
                    '<svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="dots" width="14" height="14" patternUnits="userSpaceOnUse"><circle cx="3" cy="3" r="2" fill="white" /></pattern></defs><rect width="100%" height="100%" fill="url(#dots)" /></svg>',
                'waves' =>
                    '<svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="waves" width="20" height="10" patternUnits="userSpaceOnUse"><path d="M0 5 Q5 0 10 5 T20 5" stroke="white" stroke-width="1.5" fill="none" /></pattern></defs><rect width="100%" height="100%" fill="url(#waves)" /></svg>',
                'stripes' =>
                    '<svg class="absolute inset-0 w-full h-full opacity-20" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="stripes" width="16" height="16" patternTransform="rotate(45)" patternUnits="userSpaceOnUse"><line x1="0" y1="0" x2="0" y2="16" stroke="white" stroke-width="6" /></pattern></defs><rect width="100%" height="100%" fill="url(#stripes)" /></svg>',
                'none' => '',
            ];
        @endphp

        <div class="relative overflow-hidden rounded-2xl text-white shadow-lg"
            style="background: linear-gradient(135deg, {{ $coverStyle['color_from'] }}, {{ $coverStyle['color_to'] }});">
            <div class="absolute -right-6 -bottom-8 size-28 rounded-full border-4 border-white/20"></div>
            <div class="absolute -right-2 -bottom-14 size-28 rounded-full border-4 border-white/10"></div>
            {!! $patternSvgMap[$coverStyle['pattern']] ?? $patternSvgMap['stripes'] !!}

            <div class="relative p-5 md:p-8 flex flex-col gap-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="p-3 rounded-xl bg-white/15 backdrop-blur border border-white/20 shrink-0">
                            <i data-lucide="{{ $coverStyle['icon'] }}" class="size-6"></i>
                        </div>
                        <div>
                            <a href="{{ route('bill_suppliers.index') }}" id="bs-back-link"
                                class="text-sm font-semibold text-white/90 hover:text-white inline-flex items-center gap-1 mb-1">
                                <i data-lucide="arrow-left" class="size-4"></i> {{ __('bill-supplier.show.back') }}
                            </a>
                            <h1 class="text-2xl md:text-3xl font-bold leading-tight">{{ $supplier->name }}</h1>
                            <p class="text-sm text-white/80 mt-0.5">{{ __('bill-supplier.show.subtitle') }}</p>
                        </div>
                    </div>

                    @unless ($supplier->trashed())
                        <a href="{{ route('bill_suppliers.create', ['supplier_id' => $supplier->id, 'week_start' => request('week_start'), 'week_end' => request('week_end')]) }}"
                            class="inline-flex items-center gap-2 py-2.5 px-4 text-sm font-semibold rounded-xl
                    bg-white text-(--color-primary) hover:bg-white/90 shadow-sm cursor-pointer whitespace-nowrap">
                            <i data-lucide="plus" class="size-4"></i>
                            {{ __('bill-supplier.show.create_bill') }}
                        </a>
                    @endunless
                </div>

                <div id="bs-summary" class="grid grid-cols-2 md:grid-cols-3 gap-3"></div>
            </div>
        </div>

        <div class="flex flex-wrap items-end justify-end gap-3">
            <div>
                <label for="filter-date-range"
                    class="block text-sm mb-2 font-medium text-(--color-dark) dark:text-(--color-light)">
                    {{ __('bill-supplier.filter.date_range') }}
                </label>
                <x-datepicker id="filter-date-range" name="date_range" mode="range" clearable="true"
                    bgClass="bg-(--color-light) dark:bg-(--color-dark)" roundedClass="rounded-lg" />
            </div>

            <x-button-loading type="button" id="filter-week-reset" icon="rotate-ccw"
                text="{{ __('bill-supplier.filter.reset') }}" loadingText="{{ __('button-loading.Saving...') }}"
                color="bg-(--color-danger) hover:bg-(--color-danger)/70"
                textColor="text-(--color-light) hover:text-(--color-light)" size="py-2 px-4 text-[15px]"
                rounded="rounded-lg" class="cursor-pointer" />
        </div>

        <div id="bs-loading"
            class="py-20 flex flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-(--color-gray)/25 bg-(--color-light) dark:bg-(--color-dark)">
            <div class="relative flex items-center justify-center size-14">
                <span class="absolute inset-0 rounded-full border-4 border-(--color-primary)/15"></span>
                <span
                    class="absolute inset-0 rounded-full border-4 border-(--color-primary) border-t-transparent animate-spin"></span>
                <i data-lucide="receipt" class="size-5 text-(--color-primary)"></i>
            </div>
            <p class="text-sm text-(--color-dark-gray)">{{ __('bill-supplier.show.loading') }}</p>
        </div>

        <div id="bs-empty"
            class="py-20 flex flex-col items-center justify-center gap-3 rounded-2xl border border-dashed border-(--color-gray)/25 bg-(--color-light) dark:bg-(--color-dark) text-center">
            <div class="p-4 rounded-full bg-(--color-gray)/10">
                <i data-lucide="inbox" class="size-8 text-(--color-gray)"></i>
            </div>
            <p class="text-sm font-medium">{{ __('bill-supplier.show.empty') }}</p>
        </div>

        <div id="bs-weeks" class="hidden space-y-0"></div>
    </div>
@endsection
