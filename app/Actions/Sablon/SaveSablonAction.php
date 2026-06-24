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
            // FIX #2: additional_fee adalah array of {nominal, notes}
            // Pastikan selalu disimpan sebagai array yang bersih
            $additionalFee = [];
            if (isset($detail['additional_fee']) && is_array($detail['additional_fee'])) {
                $additionalFee = array_values(
                    array_map(fn($af) => [
                        'nominal' => (float) ($af['nominal'] ?? 0),
                        'notes'   => (string) ($af['notes'] ?? ''),
                    ], $detail['additional_fee'])
                );
            }

            // Hitung total dari semua additional_fee
            $additionalTotal = array_sum(array_column($additionalFee, 'nominal'));
            $fee             = (float) ($detail['fee'] ?? 0);

            $sablon->sablonEmployeeDetails()->create([
                'fabric_detail_id'   => $detail['fabric_detail_id'] ?? null,
                'employee_id'        => $detail['employee_id'],
                'layers'             => $detail['layers'] ?? 0,
                'fee'                => $fee,
                'additional_fee'     => $additionalFee,
                'total'              => $detail['total'] ?? ($fee + $additionalTotal),
                'is_change'          => $detail['is_change'] ?? false,
                'employee_change_id' => $detail['employee_change_id'] ?? null,
                'is_payed'           => $detail['is_payed'] ?? false,
                'notes'              => $detail['notes'] ?? null,
            ]);
        }
    }
}
