<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierBillCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                     => $this->id,
            'name'                   => $this->name,
            'address'                => $this->address,
            'contact'                => $this->contact,
            'is_active'              => $this->is_active,
            'unbilled_sablons_count' => $this->unbilled_sablons_count ?? 0,
            'unpaid_bills_count'     => $this->unpaid_bills_count ?? 0,
            'total_unpaid'           => (int) ($this->total_unpaid ?? 0),
            'total_unpaid_formatted' => RupiahHelper::format($this->total_unpaid ?? 0),
            'deleted_at'             => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
