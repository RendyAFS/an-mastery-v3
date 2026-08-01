<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FabricResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'supplier_id'            => $this->supplier_id,
            'type_fabric_id'         => $this->type_fabric_id,
            'date_coming'            => $this->date_coming?->format('Y-m-d'),
            'code'                   => $this->code,
            'seri'                   => $this->seri,
            'stock_total'            => $this->stock_total,
            'stock_summary'          => $this->available_stock_summary,
            'total_inventory_fabric' => $this->total_inventory_fabric,
            'notes'                  => $this->notes,
            'created_at'             => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'             => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'             => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'             => $this->created_by,
            'updated_by'             => $this->updated_by,
            'deleted_by'             => $this->deleted_by,
            'supplier'               => new SupplierResource($this->whenLoaded('supplier')),
            'type_fabric'            => new TypeFabricResource($this->whenLoaded('typeFabric')),
            'fabric_details'         => FabricDetailResource::collection($this->whenLoaded('fabricDetails')),
        ];
    }
}
