<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SablonDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'sablon_id'        => $this->sablon_id,
            'fabric_detail_id' => $this->fabric_detail_id,
            'color_fabric_id'  => $this->color_fabric_id,
            'long_fabric'      => $this->long_fabric,
            
            'sablon'           => new SablonResource($this->whenLoaded('sablon')),
            'fabricDetail'     => new FabricDetailResource($this->whenLoaded('fabricDetail')),
            'colorFabric'      => new ColorFabricResource($this->whenLoaded('colorFabric')),
        ];
    }
}
