<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'address'    => $this->address,
            'contact'    => $this->contact,
            'notes'      => $this->notes,
            'created_at' => $this->created_at->toDateTimeLocalString(),
            'updated_at' => $this->updated_at->toDateTimeLocalString(),
            'deleted_at' => $this->deleted_at?->toDateTimeLocalString() ?? null,
        ];
    }
}
