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
            'batch'             => $this->batch,
            'sablon_id'         => $this->sablon_id,
            'sablon'            => $this->whenLoaded('sablon', fn() => $this->sablon ? [
                'id'                => $this->sablon->id,
                'total_long_fabric' => $this->sablon->total_long_fabric,
                'total_sablon'      => $this->sablon->total_sablon,
                'date_sablon'       => $this->sablon->date_sablon?->format('Y-m-d'),
                'status'            => $this->sablon->status?->value,
                'status_label'      => $this->sablon->status?->labels(),
                'image_fabric'      => $this->sablon->imageFabric?->name,
                'type_fabric'       => $this->sablon->typeFabric?->name,
                'type_color'        => $this->sablon->typeColor?->name,
            ] : null),
            'price_supplier_id'   => $this->price_supplier_id,
            'total_fee'           => $this->total_fee,
            'total_fee_formatted' => RupiahHelper::format($this->total_fee),
            'date_bill'           => $this->date_bill?->format('Y-m-d'),
            'is_paid'             => $this->is_paid,
            'is_delivered'        => $this->is_delivered,
            'notes'               => $this->notes,
            'created_at'          => $this->created_at?->format('Y-m-d H:i:s'),
            'deleted_at'          => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
