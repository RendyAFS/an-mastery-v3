<?php

namespace App\Actions\Sablon;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Http\Requests\Sablon\SaveSablonRequest;
use App\Models\Sablon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class SaveSablonAction
{
    public function __construct(
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction,
        private SettleBonAction $settleBonAction,
        private SettleLateCompletionAction $settleLateCompletionAction
    ) {}

    private function syncEmployeeDetails(Sablon $sablon, array $employeeDetails): array
    {
        $existingDetails = $sablon->sablonEmployeeDetails()->with('salaryEmployee')->get();

        $affectedEmployeeIds = $existingDetails->pluck('employee_id')
            ->merge($existingDetails->pluck('employee_change_id'))
            ->merge(collect($employeeDetails)->pluck('employee_id'))
            ->merge(collect($employeeDetails)->pluck('employee_change_id'))
            ->filter()
            ->unique()
            ->values();

        $staleWeekPairs = $existingDetails
            ->filter(fn($d) => $d->salaryEmployee)
            ->map(fn($d) => $d->employee_id . '|' . \Carbon\Carbon::parse($d->salaryEmployee->date)->toDateString())
            ->unique()
            ->values();

        $sablon->sablonEmployeeDetails()
            ->where(function ($q) {
                $q->where('is_paid', false)->orWhereNull('is_paid');
            })
            ->where('is_settled', false)
            ->whereNull('settlement_of_id')
            ->whereNull('late_eligible_at')
            ->delete();

        foreach ($employeeDetails as $detail) {
            $id = $detail['id'] ?? null;

            if ($id && $sablon->sablonEmployeeDetails()->whereKey($id)->exists()) {
                $sablon->sablonEmployeeDetails()->whereKey($id)->update([
                    'employee_id'        => $detail['employee_id'] ?? $sablon->sablonEmployeeDetails()->whereKey($id)->value('employee_id'),
                    'layers'             => $detail['layers'] ?? 0,
                    'fee'                => $detail['fee'] ?? 0,
                    'is_change'          => $detail['is_change'] ?? false,
                    'employee_change_id' => $detail['employee_change_id'] ?? null,
                    'is_bon'             => $detail['is_bon'] ?? false,
                    'is_paid'            => $detail['is_paid'] ?? false,
                    'notes'              => $detail['notes'] ?? null,
                    'additional_fee'     => $detail['is_bon'] ?? false
                        ? array_values(
                            array_map(fn($af) => [
                                'nominal' => (float) ($af['nominal'] ?? 0),
                                'notes'   => (string) ($af['notes'] ?? ''),
                            ], $detail['additional_fees'] ?? [])
                        )
                        : [],
                ]);
                continue;
            }

            $sablon->sablonEmployeeDetails()->create([
                'fabric_detail_id'   => $detail['fabric_detail_id'] ?? null,
                'employee_id'        => $detail['employee_id'],
                'layers'             => $detail['layers'] ?? 0,
                'fee'                => $detail['fee'] ?? 0,
                'additional_fee'     => array_values(
                    array_map(fn($af) => [
                        'nominal' => (float) ($af['nominal'] ?? 0),
                        'notes'   => (string) ($af['notes'] ?? ''),
                    ], $detail['additional_fees'] ?? [])
                ),
                'is_change'          => $detail['is_change'] ?? false,
                'employee_change_id' => $detail['employee_change_id'] ?? null,
                'is_bon'             => $detail['is_bon'] ?? false,
                'is_paid'            => $detail['is_paid'] ?? false,
                'notes'              => $detail['notes'] ?? null,
            ]);
        }

        return [
            'affected_employee_ids' => $affectedEmployeeIds,
            'stale_week_pairs'      => $staleWeekPairs,
        ];
    }

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
            $syncResult = $this->syncEmployeeDetails($sablon, $employeeDetails);

            $this->settleBonAction->handle($sablon);
            $this->settleLateCompletionAction->handle($sablon);

            $this->upsertSalaryEmployeeAction->syncSablonEmployeeDetails($sablon);

            foreach ($syncResult['stale_week_pairs'] as $pair) {
                [$employeeId, $weekStart] = explode('|', $pair);
                $this->upsertSalaryEmployeeAction->handleForEmployee((int) $employeeId, $weekStart);
            }

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

    private function upsertSalaryForAffectedEmployees(Sablon $sablon, Collection $affectedEmployeeIds): void
    {
        if (! $sablon->date_sablon || $affectedEmployeeIds->isEmpty()) {
            return;
        }

        $weeksToProcess = collect([
            $sablon->date_sablon->toDateString(),
            now()->toDateString(),
        ])->unique();

        $affectedEmployeeIds->each(function ($employeeId) use ($weeksToProcess) {
            $weeksToProcess->each(function ($weekOf) use ($employeeId) {
                $this->upsertSalaryEmployeeAction->handleForEmployee((int) $employeeId, $weekOf);
            });
        });
    }
}
