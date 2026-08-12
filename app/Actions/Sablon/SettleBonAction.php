<?php

namespace App\Actions\Sablon;

use App\Enums\StatusSablonEnum;
use App\Models\Sablon;
use App\Models\SablonEmployeeDetail;
use Illuminate\Support\Facades\DB;

class SettleBonAction
{
    public function handle(Sablon $sablon): int
    {
        if (! in_array($sablon->status, [
            StatusSablonEnum::DONE,
            StatusSablonEnum::DELIVERED,
        ])) {
            return 0;
        }

        $bonDetails = $sablon->sablonEmployeeDetails()
            ->where('is_bon', true)
            ->where('is_settled', false)
            ->get();

        if ($bonDetails->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($bonDetails) {
            foreach ($bonDetails as $detail) {
                $advanceTotal = collect($detail->additional_fee ?? [])
                    ->sum(fn($af) => (float) ($af['nominal'] ?? 0));

                $settlementAmount = (float) $detail->fee - $advanceTotal;

                SablonEmployeeDetail::create([
                    'sablon_id'           => $detail->sablon_id,
                    'employee_id'         => $detail->employee_id,
                    'layers'              => $detail->layers,
                    'fee'                 => $settlementAmount,
                    'additional_fee'      => [],
                    'is_change'           => false,
                    'employee_change_id'  => null,
                    'is_bon'              => false,
                    'is_paid'             => false,
                    'is_settled'          => false,
                    'settlement_of_id'    => $detail->id,
                    'settled_at'          => now()->toDateString(),
                    'notes'               => __('sablon.settlement_notes', ['id' => $detail->id]),
                ]);

                $detail->update(['is_settled' => true]);
            }
        });

        return $bonDetails->count();
    }
}
