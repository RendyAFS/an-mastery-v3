<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sablonGroups = $this->sablonEmployeeDetails
            ->groupBy(fn($detail) => $detail->sablon?->supplier?->name ?? '-')
            ->map(function ($details, $supplierName) {
                return [
                    'supplier_name' => $supplierName,
                    'items' => $details->map(function ($detail) {
                        $detailFee = (float) $detail->fee
                            + collect($detail->additional_fee ?? [])->sum(fn($af) => (float) ($af['nominal'] ?? 0));

                        return [
                            'image_fabric_name' => $detail->sablon?->imageFabric?->name ?? '-',
                            'layers'            => $detail->layers,
                            'fee'               => $detailFee,
                            'fee_formated'      => RupiahHelper::format($detailFee),
                        ];
                    })->values(),
                ];
            })
            ->values();

        $additionalFeeTotal = collect($this->additional_fee ?? [])->sum(fn($af) => (float) ($af['nominal'] ?? 0));
        $total = (float) $this->fee + $additionalFeeTotal;

        return [
            'id'                            => $this->id,
            'employee_id'                   => $this->employee_id,
            'employee'                      => new EmployeeResource($this->whenLoaded('employee')),
            'fee'                           => $this->fee,
            'fee_formated'                  => RupiahHelper::format($this->fee),
            'additional_fee'                => $this->additional_fee,
            'additional_fee_total'          => $additionalFeeTotal,
            'additional_fee_total_formated' => RupiahHelper::format($additionalFeeTotal),
            'total'                         => $total,
            'total_formated'                => RupiahHelper::format($total),
            'status'                        => $this->status,
            'date'                          => $this->date?->format('Y-m-d'),
            'notes'                         => $this->notes,
            'created_at'                    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'                    => $this->updated_at?->format('Y-m-d H:i:s'),
            'sablon_groups'                 => $sablonGroups,
        ];
    }
}
