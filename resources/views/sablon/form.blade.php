@php
    $detailsJson = ($sablon->sablonDetails ?? collect())
        ->map(
            fn($d) => [
                'fabric_detail_id' => $d->fabric_detail_id,
                'color_fabric_id' => $d->color_fabric_id,
                'long_fabric' => $d->long_fabric,
            ],
        )
        ->values();

    $employeeDetailsJson = ($sablon->sablonEmployeeDetails ?? collect())
        ->map(
            fn($d) => [
                'id' => $d->id,
                'fabric_detail_id' => $d->fabric_detail_id,
                'employee_id' => $d->employee_id,
                'layers' => $d->layers,
                'fee' => $d->fee,
                'is_change' => $d->is_change,
                'employee_change_id' => $d->employee_change_id,
                'is_bon' => $d->is_bon,
                'is_paid' => $d->is_paid,
                'notes' => $d->notes,
                'additional_fees' => $d->additional_fee ?? [],
                'locked' => ($d->is_paid && ! $d->is_bon) || $d->is_settled || (bool) $d->settlement_of_id,
                'is_settled' => (bool) $d->is_settled,
                'is_settlement_row' => (bool) $d->settlement_of_id,
            ],
        )
        ->values();

    $statusOptions = __('sablon.statuses');
@endphp

<div x-data="sablonForm(
    {{ Js::from($detailsJson) }},
    {{ Js::from($employeeDetailsJson) }},
    {{ Js::from($fabricDetails) }},
    {{ Js::from($employees) }},
    {{ Js::from($priceEmployeesRaw) }},
    {{ Js::from($typeColorsRaw) }},
    {{ Js::from($sablon?->fabric_id ?? null) }},
    {{ Js::from($sablon?->supplier_id ?? null) }},
    {{ Js::from($fabrics) }},
    {{ Js::from($priceEmployeeMap) }},
    {{ Js::from($imageFabrics) }},
    {{ Js::from($sablon?->image_fabric_id ?? null) }},
    {{ Js::from($sablon?->id ?? null) }}
)" x-init="init()">

    @include('sablon.partials._main_info', ['statusOptions' => $statusOptions])
    @include('sablon.partials._fabric-detail')
    @include('sablon.partials._employee-detail')
</div>

@include('sablon.partials._modal-salary-additional-fee')
