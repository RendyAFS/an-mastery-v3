<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SablonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'supplier_id'       => $this->supplier_id,
            'fabric_id'         => $this->fabric_id,
            'image_fabric_id'   => $this->image_fabric_id,
            'type_color_id'     => $this->type_color_id,
            'type_fabric_id'    => $this->type_fabric_id,
            'price_employee_id' => $this->price_employee_id,
            'totalLongFabric'   => $this->totalLongFabric,
            'totalSablon'       => $this->totalSablon,
            'dateSablon'        => $this->dateSablon,
            'status'            => $this->status,
            'notes'             => $this->notes,
            'created_at'        => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'        => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'        => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'        => $this->created_by,
            'updated_by'        => $this->updated_by,
            'deleted_by'        => $this->deleted_by,

            'supplier'              => new SupplierResource($this->whenLoaded('supplier')),
            'fabric'                => new FabricResource($this->whenLoaded('fabric')),
            'imageFabric'           => new ImageFabricResource($this->whenLoaded('imageFabric')),
            'typeColor'             => new TypeColorResource($this->whenLoaded('typeColor')),
            'typeFabric'            => new TypeFabricResource($this->whenLoaded('typeFabric')),
            'priceEmployee'         => new PriceEmployeeResource($this->whenLoaded('priceEmployee')),
            'fabricDetails'         => SablonDetailResource::collection($this->whenLoaded('fabricDetails')),
            'sablonDetails'         => SablonDetailResource::collection($this->whenLoaded('sablonDetails')),
            'sablonEmployeeDetails' => SablonEmployeeDetailResource::collection($this->whenLoaded('sablonEmployeeDetails')),
        ];
    }
}
