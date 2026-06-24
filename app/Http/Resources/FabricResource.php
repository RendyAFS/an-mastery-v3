<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FabricResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'supplier_id'    => $this->supplier_id,
            'code'           => $this->code,
            'seri'           => $this->seri,
            'stock_total'    => $this->stock_total,
            'stock_summary'  => $this->stock_summary,
            'notes'          => $this->notes,
            'created_at'     => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'     => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'     => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'     => $this->created_by,
            'updated_by'     => $this->updated_by,
            'deleted_by'     => $this->deleted_by,
            'supplier'       => new SupplierResource($this->whenLoaded('supplier')),
            'fabric_details' => FabricDetailResource::collection($this->whenLoaded('fabricDetails')),
        ];
    }
}
