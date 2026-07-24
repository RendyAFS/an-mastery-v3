<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillSupplierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'supplier_id'       => $this->supplier_id,
            'sablon_id'         => $this->sablon_id,
            'sablon'            => $this->whenLoaded('sablon', fn() => $this->sablon ? [
                'id'                => $this->sablon->id,
                'total_long_fabric' => $this->sablon->total_long_fabric,
                'total_sablon'      => $this->sablon->total_sablon,
                'date_sablon'       => $this->sablon->date_sablon?->format('Y-m-d'),
                'status'            => $this->sablon->status?->value,
                'status_label'      => $this->sablon->status?->labels(),
                'fabric'            => $this->sablon->fabric?->name,
                'type_fabric'       => $this->sablon->typeFabric?->name,
                'type_color'        => $this->sablon->typeColor?->name,
            ] : null),
            'price_supplier_id'   => $this->price_supplier_id,
            'price'               => $this->whenLoaded('priceSupplier', fn() => $this->priceSupplier?->price),
            'price_formatted'     => $this->whenLoaded('priceSupplier', fn() => RupiahHelper::format($this->priceSupplier?->price ?? 0)),
            'total_fee'           => $this->total_fee,
            'total_fee_formatted' => RupiahHelper::format($this->total_fee),
            'date_bill'           => $this->date_bill?->format('Y-m-d'),
            'is_paid'             => $this->is_paid,
            'notes'               => $this->notes,
            'details'             => $this->whenLoaded('details', fn() => $this->details->map(fn($d) => [
                'id'            => $d->id,
                'fabric_detail' => $d->sablonDetail?->fabricDetail?->fabric?->name,
                'color_fabric'  => $d->sablonDetail?->colorFabric?->name,
                'long_fabric'   => $d->sablonDetail?->long_fabric,
            ])),
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'deleted_at' => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
