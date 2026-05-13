<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceSupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'supplier_id'     => $this->supplier_id,
            'type_fabric_id'  => $this->type_fabric_id,
            'type_color_id'   => $this->type_color_id,
            'price'           => $this->price,
            'price_formatted' => RupiahHelper::format($this->price),
            'notes'           => $this->notes,
            'created_at'      => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'      => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'      => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'      => $this->created_by,
            'updated_by'      => $this->updated_by,
            'deleted_by'      => $this->deleted_by,

            // Relation
            'supplier'       => new SupplierResource($this->whenLoaded('supplier')),
            'type_fabric'    => new TypeFabricResource($this->whenLoaded('typeFabric')),
            'type_color'     => new TypeColorResource($this->whenLoaded('typeColor')),
        ];
    }
}
