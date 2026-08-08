<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'employee_id'   => $this->employee_id,
            'employee_name' => $this->employee?->name ?? '-',
            'employee'      => new EmployeeResource($this->whenLoaded('employee')),
            'name'          => $this->name,
            'nominal'       => $this->nominal,
            'date'          => $this->date?->translatedFormat('d F Y'),
            'is_paid'       => (bool) $this->is_paid,
            'created_at'    => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'    => $this->updated_at?->format('Y-m-d H:i:s'),
            'deleted_at'    => $this->deleted_at?->format('Y-m-d H:i:s'),
        ];
    }
}
