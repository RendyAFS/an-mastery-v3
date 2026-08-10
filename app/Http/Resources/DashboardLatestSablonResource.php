<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardLatestSablonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'supplier'              => $this->supplier?->name,
            'image_fabric'          => $this->imageFabric?->name,
            'type_fabric'           => $this->typeFabric?->name,
            'type_color'            => $this->typeColor?->name,
            'date_sablon'           => $this->date_sablon?->format('Y-m-d'),
            'status'                => $this->status?->value,
            'status_label'          => $this->status?->labels(),
            'total_sablon'          => $this->total_sablon,
            'total_sablon_formated' => RupiahHelper::format($this->total_sablon),
        ];
    }
}
