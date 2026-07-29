<?php

namespace App\Http\Resources;

use App\Helpers\RupiahHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SablonEmployeeDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'sablon_id'          => $this->sablon_id,
            'fabric_detail_id'   => $this->fabric_detail_id,
            'employee_id'        => $this->employee_id,
            'layers'             => $this->layers,
            'fee'                => $this->fee,
            'additional_fee'     => $this->additional_fee,
            'total'              => $this->total,
            'total_formated'     => RupiahHelper::format($this->total),
            'is_change'          => $this->is_change,
            'employee_change_id' => $this->employee_change_id,
            'is_bon'             => $this->is_bon,
            'is_payed'           => $this->is_payed,
            'notes'              => $this->notes,
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'         => $this->updated_at?->format('Y-m-d H:i:s'),
            'created_by'         => $this->created_by,
            'updated_by'         => $this->updated_by,

            'sablon'             => new SablonResource($this->whenLoaded('sablon')),
            'fabricDetail'       => new FabricDetailResource($this->whenLoaded('fabricDetail')),
            'employee'           => new EmployeeResource($this->whenLoaded('employee')),
            'employeeChange'     => new EmployeeResource($this->whenLoaded('employeeChange')),
        ];
    }
}
