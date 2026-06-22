<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'employee_id' => $this->employee_id,
            'week_of'     => $this->week_of,
            'monday'      => $this->monday,
            'tuesday'     => $this->tuesday,
            'wednesday'   => $this->wednesday,
            'thursday'    => $this->thursday,
            'friday'      => $this->friday,
            'saturday'    => $this->saturday,
            'sunday'      => $this->sunday,
            'total'       => $this->total,
            'notes'       => $this->notes,
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),

            'employee'    => new EmployeeResource($this->whenLoaded('employee')),
        ];
    }
}
