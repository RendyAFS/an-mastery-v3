<?php

namespace App\Actions\BillSupplier;

use App\Models\BillSupplier;
use App\Models\PriceSupplier;
use App\Models\Sablon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaveBillSupplierAction
{
    public function handle(Request $request, ?BillSupplier $billSupplier = null): BillSupplier|Collection
    {
        return DB::transaction(function () use ($request, $billSupplier) {

            if (! $billSupplier) {
                return $this->createBulk($request);
            }

            $billSupplier->update([
                'date_bill' => $request->input('date_bill'),
                'is_paid'   => $request->boolean('is_paid'),
                'notes'     => $request->input('notes'),
            ]);

            return $billSupplier->load(['sablon', 'priceSupplier', 'details']);
        });
    }

    private function createBulk(Request $request): Collection
    {
        $sablonIds = $request->input('sablon_ids', []);

        $createdBills = collect();

        foreach ($sablonIds as $sablonId) {
            $sablon = Sablon::findOrFail($sablonId);

            $priceSupplier = PriceSupplier::query()
                ->where('supplier_id', $sablon->supplier_id)
                ->where('type_fabric_id', $sablon->type_fabric_id)
                ->where('type_color_id', $sablon->type_color_id)
                ->first();

            if (! $priceSupplier) {
                throw ValidationException::withMessages([
                    'sablon_ids' => "Harga supplier untuk sablon #{$sablon->id} belum tersedia.",
                ]);
            }

            $bill = BillSupplier::create([
                'supplier_id'       => $sablon->supplier_id,
                'price_supplier_id' => $priceSupplier->id,
                'sablon_id'         => $sablon->id,
                'total_fee'         => $sablon->total_long_fabric * $priceSupplier->price,
                'date_bill'         => $request->input('date_bill'),
                'is_paid'           => $request->boolean('is_paid'),
                'notes'             => $request->input('notes'),
            ]);

            $details = $sablon->sablonDetails->map(fn($detail) => [
                'bill_supplier_id' => $bill->id,
                'sablon_detail_id' => $detail->id,
            ])->toArray();

            if (! empty($details)) {
                $bill->details()->insert($details);
            }

            $createdBills->push($bill->load(['sablon', 'priceSupplier', 'details']));
        }

        return $createdBills;
    }
}
