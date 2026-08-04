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
                'fabric_detail_id' => $d->fabric_detail_id,
                'employee_id' => $d->employee_id,
                'layers' => $d->layers,
                'fee' => $d->fee,
                'is_change' => $d->is_change,
                'employee_change_id' => $d->employee_change_id,
                'is_bon' => $d->is_bon,
                'is_paid' => $d->is_paid,
                'notes' => $d->notes,
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
    {{ Js::from($fabrics) }}
)" x-init="init()">

    @include('sablon.partials._main_info', ['statusOptions' => $statusOptions])
    @include('sablon.partials._fabric-detail')
    @include('sablon.partials._employee-detail')
</div>
