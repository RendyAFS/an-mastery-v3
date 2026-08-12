<?php

namespace App\Actions\Sablon;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Http\Requests\Sablon\SaveSablonRequest;
use App\Models\Sablon;
use Illuminate\Support\Facades\DB;

class SaveSablonAction
{
    public function __construct(
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function handle(SaveSablonRequest $request, ?Sablon $sablon = null): Sablon
    {
        $data = $request->validated();

        $fabricDetails   = $data['fabric_details'] ?? [];
        $employeeDetails = $data['employee_details'] ?? [];

        unset($data['fabric_details'], $data['employee_details']);

        return DB::transaction(function () use ($data, $fabricDetails, $employeeDetails, $sablon) {
            $sablon = $sablon
                ? tap($sablon)->update($data)
                : Sablon::create($data);

            $this->syncFabricDetails($sablon, $fabricDetails);
            $this->syncEmployeeDetails($sablon, $employeeDetails);

            return $sablon->load(['sablonDetails', 'sablonEmployeeDetails']);
        });
    }

    private function syncFabricDetails(Sablon $sablon, array $fabricDetails): void
    {
        $sablon->sablonDetails()->delete();

        foreach ($fabricDetails as $detail) {
            $sablon->sablonDetails()->create([
                'fabric_detail_id' => $detail['fabric_detail_id'],
                'color_fabric_id'  => $detail['color_fabric_id'],
                'long_fabric'      => $detail['long_fabric'],
            ]);
        }
    }

    private function syncEmployeeDetails(Sablon $sablon, array $employeeDetails): void
    {
        $affectedEmployeeIds = $sablon->sablonEmployeeDetails()
            ->pluck('employee_id')
            ->merge(collect($employeeDetails)->pluck('employee_id'))
            ->filter()
            ->unique()
            ->values();

        $additionalFeesByEmployee = collect($employeeDetails)
            ->groupBy('employee_id')
            ->map(fn($details) => $details->flatMap(fn($detail) => $detail['additional_fees'] ?? [])->values());

        $sablon->sablonEmployeeDetails()->delete();

        foreach ($employeeDetails as $detail) {
            $sablon->sablonEmployeeDetails()->create([
                'fabric_detail_id'   => $detail['fabric_detail_id'] ?? null,
                'employee_id'        => $detail['employee_id'],
                'layers'             => $detail['layers'] ?? 0,
                'fee'                => $detail['fee'] ?? 0,
                'is_change'          => $detail['is_change'] ?? false,
                'employee_change_id' => $detail['employee_change_id'] ?? null,
                'is_bon'             => $detail['is_bon'] ?? false,
                'is_paid'            => false,
                'notes'              => $detail['notes'] ?? null,
            ]);
        }

        if (! $sablon->date_sablon || $affectedEmployeeIds->isEmpty()) {
            return;
        }

        $weekOf = $sablon->date_sablon->toDateString();

        $affectedEmployeeIds->each(function ($employeeId) use ($weekOf, $additionalFeesByEmployee) {
            $additionalFees = $additionalFeesByEmployee->get($employeeId, collect())->all();

            $this->upsertSalaryEmployeeAction->handleForEmployee(
                (int) $employeeId,
                $weekOf,
                null,
                $additionalFees ?: null,
                null,
                true
            );
        });
    }
}
