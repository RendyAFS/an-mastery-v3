<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BonusResource extends JsonResource
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
            'min'             => $this->min,
            'min_formatted'   => RupiahHelper::format($this->min),
            'bonus'           => $this->bonus,
            'bonus_formatted' => RupiahHelper::format($this->bonus),
            'notes'           => $this->notes,
            'created_at'      => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'      => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'      => $this->deleted_at?->format('Y-m-d H:i:s'),
            'created_by'      => $this->created_by,
            'updated_by'      => $this->updated_by,
            'deleted_by'      => $this->deleted_by,
        ];
    }
}
