<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryEmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sablonFeeTotal = $this->sablonEmployeeDetails->sum(fn($detail) => (float) $detail->fee);

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

        $presenceTotal      = (float) ($this->presence?->total ?? 0);
        $additionalFeeTotal = collect($this->additional_fee ?? [])->sum(fn($af) => (float) ($af['nominal'] ?? 0));
        $memoTotal          = $this->memos->sum('nominal');
        $total              = $sablonFeeTotal + $presenceTotal + $additionalFeeTotal + $memoTotal;

        return [
            'id'                            => $this->id,
            'employee_id'                   => $this->employee_id,
            'employee'                      => new EmployeeResource($this->whenLoaded('employee')),
            'fee'                           => $sablonFeeTotal,
            'fee_formated'                  => RupiahHelper::format($sablonFeeTotal),
            'additional_fee'                => $this->additional_fee,
            'additional_fee_total'          => $additionalFeeTotal,
            'additional_fee_total_formated' => RupiahHelper::format($additionalFeeTotal),
            'presence_total'                => $presenceTotal,
            'presence_total_formated'       => RupiahHelper::format($presenceTotal),
            'total'                         => $total,
            'total_formated'                => RupiahHelper::format($total),
            'status'                        => $this->status,
            'date'                          => $this->date?->format('Y-m-d'),
            'notes'                         => $this->notes,
            'created_at'                    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'                    => $this->updated_at?->format('Y-m-d H:i:s'),
            'sablon_groups'                 => $sablonGroups,
            'memos' => $this->memos->map(fn($m) => [
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
