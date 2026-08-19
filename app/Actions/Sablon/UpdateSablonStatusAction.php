<?php

namespace App\Actions\Sablon;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Models\Sablon;

class UpdateSablonStatusAction
{
    public function __construct(
        private SettleBonAction $settleBonAction,
        private SettleLateCompletionAction $settleLateCompletionAction,
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function handle(Sablon $sablon, string $status): Sablon
    {
        $sablon->update(['status' => $status]);

        $this->settleBonAction->handle($sablon);
        $this->settleLateCompletionAction->handle($sablon);

        if ($sablon->date_sablon) {
            $affectedEmployeeIds = $sablon->sablonEmployeeDetails()
                ->pluck('employee_id')
                ->unique();

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

        return $sablon;
    }
}
