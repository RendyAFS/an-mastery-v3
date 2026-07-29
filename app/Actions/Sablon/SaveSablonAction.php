<?php

namespace App\Actions\Sablon;

use App\Http\Requests\Sablon\SaveSablonRequest;
use App\Models\Sablon;
use Illuminate\Support\Facades\DB;

class SaveSablonAction
{
    public function handle(SaveSablonRequest $request, ?Sablon $sablon = null): Sablon
    {
        $data = $request->validated();

        $fabricDetails   = $data['fabric_details'] ?? [];
        $employeeDetails = $data['employee_details'] ?? [];

        unset($data['fabric_details'], $data['employee_details']);

        return DB::transaction(function () use ($data, $fabricDetails, $employeeDetails, $sablon) {
            $sablon = $sablon
                ? tap($sablon)->update($data)
                : Sablon::create($data);

            $this->syncFabricDetails($sablon, $fabricDetails);
            $this->syncEmployeeDetails($sablon, $employeeDetails);

            return $sablon->load(['sablonDetails', 'sablonEmployeeDetails']);
        });
    }

    private function syncFabricDetails(Sablon $sablon, array $fabricDetails): void
    {
        $sablon->sablonDetails()->delete();

        foreach ($fabricDetails as $detail) {
            $sablon->sablonDetails()->create([
                'fabric_detail_id' => $detail['fabric_detail_id'],
                'color_fabric_id'  => $detail['color_fabric_id'],
                'long_fabric'      => $detail['long_fabric'],
            ]);
        }
    }

    private function syncEmployeeDetails(Sablon $sablon, array $employeeDetails): void
    {
        $sablon->sablonEmployeeDetails()->delete();

        foreach ($employeeDetails as $detail) {
            $sablon->sablonEmployeeDetails()->create([
                'fabric_detail_id'   => $detail['fabric_detail_id'] ?? null,
                'employee_id'        => $detail['employee_id'],
                'layers'             => $detail['layers'] ?? 0,
                'fee'                => $detail['fee'] ?? 0,
                'is_change'          => $detail['is_change'] ?? false,
                'employee_change_id' => $detail['employee_change_id'] ?? null,
                'is_bon'             => $detail['is_bon'] ?? false,
                'is_paid'           => $detail['is_paid'] ?? false,
                'notes'              => $detail['notes'] ?? null,
            ]);
        }
    }
}
