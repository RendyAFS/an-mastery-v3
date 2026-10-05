<?php

namespace App\Actions\Sablon;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Models\Sablon;
use Illuminate\Support\Facades\DB;

class UpdateSablonLongFabricAction
{
    public function __construct(
        private CalculateSablonAction $calculateSablonAction,
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function handle(Sablon $sablon, array $details): Sablon
    {
        return DB::transaction(function () use ($sablon, $details) {
            foreach ($details as $detail) {
                $sablon->sablonDetails()
                    ->whereKey($detail['id'])
                    ->update(['long_fabric' => (int) round((float) $detail['long_fabric'])]);
            }

            $employeeDetails = $sablon->sablonEmployeeDetails()->get(['id', 'layers']);

            $calculation = $this->calculateSablonAction->handle([
                'price_employee_id' => $sablon->price_employee_id,
                'type_color_id'     => $sablon->type_color_id,
                'fabric_details'    => $sablon->sablonDetails()->get(['long_fabric'])->toArray(),
                'employee_details'  => $employeeDetails->map(fn($d) => ['id' => $d->id, 'layers' => $d->layers])->all(),
            ], $sablon);

            $sablon->update([
                'total_long_fabric' => (int) round($calculation['total_long_fabric']),
                'total_sablon'      => (int) round($calculation['total_sablon']),
            ]);

            foreach ($employeeDetails as $index => $employeeDetail) {
                $calc = $calculation['employee_fees'][$index] ?? null;

                if (! $calc || $calc['locked']) {
                    continue;
                }

                $employeeDetail->update(['fee' => (int) round($calc['fee'])]);
            }

            $this->upsertSalaryEmployeeAction->syncSablonEmployeeDetails($sablon);

            return $sablon->refresh()->load(['sablonDetails.colorFabric']);
        });
    }
}
