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

        $this->upsertSalaryEmployeeAction->syncSablonEmployeeDetails($sablon);

        return $sablon;
    }
}
