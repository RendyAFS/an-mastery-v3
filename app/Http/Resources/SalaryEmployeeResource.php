<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $previousWeekFees = collect($this->relationLoaded('previousPendingSalaries') ? $this->previousPendingSalaries : [])
            ->map(fn($prev) => [
                'salary_employee_id' => $prev->id,
                'date'                => $prev->date?->format('Y-m-d'),
                'nominal'             => $prev->computedTotal(),
                'nominal_formated'    => RupiahHelper::format($prev->computedTotal()),
                'notes'               => __('salary-employee.card.previous_week_fee', ['date' => $prev->date?->translatedFormat('d F Y')]),
            ])
            ->values();

        $previousWeekFeesTotal = $previousWeekFees->sum('nominal');

        $sablonFeeTotal = $this->sablonEmployeeDetails
            ->filter(fn($detail) => $detail->salary_employee_id !== null || $detail->isEligibleForSalary())
            ->sum(fn($detail) => $detail->countableAmount());

        $sablonGroups = $this->sablonEmployeeDetails
            ->groupBy(fn($detail) => $detail->sablon?->supplier?->name ?? '-')
            ->map(function ($details, $supplierName) {
                return [
                    'supplier_name' => $supplierName,
                    'items' => $details->map(function ($detail) {
                        $isCounted = $detail->salary_employee_id !== null || $detail->isEligibleForSalary();
                        $detailFee = $isCounted ? $detail->countableAmount() : 0;

                        $originalFee = $detailFee;
                        $deductionFee = 0;

                        if ($detail->settlement_of_id && $detail->settlementOf) {
                            $originalFee = (float) $detail->settlementOf->fee;
                            $deductionFee = collect($detail->settlementOf->additional_fee ?? [])
                                ->sum(fn($af) => (float) ($af['nominal'] ?? 0));
                        }

                        return [
                            'image_fabric_name'      => $detail->sablon?->imageFabric?->name ?? '-',
                            'layers'                 => $detail->layers,
                            'fee'                    => $detailFee,
                            'fee_formated'           => RupiahHelper::format($detailFee),
                            'original_fee'           => $originalFee,
                            'original_fee_formated' => RupiahHelper::format($originalFee),
                            'deduction_fee'          => $deductionFee,
                            'deduction_fee_formated' => RupiahHelper::format(abs($deductionFee)),
                            'is_eligible'            => $isCounted,
                            'is_bon'                 => (bool) $detail->is_bon,
                            'is_bon_settled'         => (bool) $detail->is_settled,
                            'is_bon_settlement'      => (bool) $detail->settlement_of_id,
                            'status'                 => $detail->sablon?->status ? __('enums.status_sablon.' . $detail->sablon->status->value) : null,
                        ];
                    })->values(),
                ];
            })
            ->values();

        $allAdditionalFees = collect($this->additional_fee ?? []);

        $isFabricAdjustment = function ($af) {
            $notes = mb_strtolower(trim($af['notes'] ?? ''));
            return str_starts_with($notes, 'plus kain') || str_starts_with($notes, 'minus kain');
        };

        $fabricAdjustments       = $allAdditionalFees->filter($isFabricAdjustment)->values();
        $otherAdditionalFees     = $allAdditionalFees->reject($isFabricAdjustment)->values();
        $fabricAdjustmentTotal   = $fabricAdjustments->sum(fn($af) => (float) ($af['nominal'] ?? 0));
        $otherAdditionalFeeTotal = $otherAdditionalFees->sum(fn($af) => (float) ($af['nominal'] ?? 0));
        $additionalFeeTotal      = $allAdditionalFees->sum(fn($af) => (float) ($af['nominal'] ?? 0));

        $totalSablonFee = $sablonFeeTotal + $fabricAdjustmentTotal;

        $presenceTotal      = (float) ($this->presence?->total ?? 0);
        $memoTotal          = $this->memos->sum('nominal');
        $total              = $sablonFeeTotal + $presenceTotal + $additionalFeeTotal + $memoTotal + $previousWeekFeesTotal;

        return [
            'id'                            => $this->id,
            'employee_id'                   => $this->employee_id,
            'employee'                      => new EmployeeResource($this->whenLoaded('employee')),
            'fee'                           => $totalSablonFee,
            'fee_formated'                  => RupiahHelper::format($totalSablonFee),
            'base_sablon_fee'               => $sablonFeeTotal,
            'base_sablon_fee_formated'      => RupiahHelper::format($sablonFeeTotal),
            'fabric_adjustments'            => $fabricAdjustments->map(fn($af) => [
                'nominal'          => (float) ($af['nominal'] ?? 0),
                'nominal_formated' => ($af['nominal'] < 0 ? '-' : '+') . ' Rp ' . number_format(abs($af['nominal']), 0, ',', '.'),
                'notes'            => $af['notes'] ?? '',
                'type'             => $af['type'] ?? 'fabric_adjustment',
            ])->values(),
            'fabric_adjustments_total'          => $fabricAdjustmentTotal,
            'fabric_adjustments_total_formated' => RupiahHelper::format($fabricAdjustmentTotal),
            'additional_fee'                => $otherAdditionalFees,
            'additional_fee_total'          => $otherAdditionalFeeTotal,
            'additional_fee_total_formated' => RupiahHelper::format($otherAdditionalFeeTotal),
            'all_additional_fee'            => $this->additional_fee ?? [],
            'presence_total'                => $presenceTotal,
            'presence_total_formated'       => RupiahHelper::format($presenceTotal),
            'previous_week_fees'            => $previousWeekFees,
            'previous_week_fees_total'      => $previousWeekFeesTotal,
            'total'                         => $total,
            'total_formated'                => RupiahHelper::format($total),
            'status'                        => $this->status,
            'date'                          => $this->date?->format('Y-m-d'),
            'week_number'                   => $this->date ? (int) $this->date->format('W') : null,
            'notes'                         => $this->notes,
            'created_at'                    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'                    => $this->updated_at?->format('Y-m-d H:i:s'),
            'sablon_groups'                 => $sablonGroups,
            'memos'                         => $this->memos->map(fn($m) => [
                'id'               => $m->id,
                'name'             => $m->name,
                'nominal'          => $m->nominal,
                'nominal_formated' => ($m->nominal < 0 ? '-' : '+') . ' Rp ' . number_format(abs($m->nominal), 0, ',', '.'),
                'date'             => $m->date?->toDateString(),
            ])->values(),
            'memo_total' => $memoTotal,
        ];
    }
}
