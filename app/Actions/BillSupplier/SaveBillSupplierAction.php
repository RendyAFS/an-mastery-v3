<?php

namespace App\Actions\BillSupplier;

use App\Enums\StatusSablonEnum;
use App\Http\Requests\BillSupplier\SaveBillSupplierRequest;
use App\Models\BillSupplier;
use App\Models\Sablon;
use App\Repositories\BillSupplierRepository;
use Illuminate\Support\Collection;

class SaveBillSupplierAction
{
    public function __construct(
        private BillSupplierRepository $billSupplierRepository
    ) {}

    public function handle(SaveBillSupplierRequest $request): Collection
    {
        $sablonIds = $request->input('sablon_ids', []);
        $batch     = now()->format('YmdHis') . now()->micro;

        $sablons = Sablon::whereIn('id', $sablonIds)->get();

        return $sablons->map(function ($sablon) use ($request, $batch) {
            $preview = $this->billSupplierRepository->calculatePreview($sablon);

            $billSupplier = BillSupplier::create([
                'supplier_id'       => $sablon->supplier_id,
                'sablon_id'         => $sablon->id,
                'price_supplier_id' => $preview['price_supplier_id'],
                'batch'             => $batch,
                'total_fee'         => $preview['total_fee'],
                'date_bill'         => $request->input('date_bill'),
                'is_paid'           => $request->boolean('is_paid'),
                'is_delivered'      => $request->boolean('is_delivered'),
                'notes'             => $request->input('notes'),
            ]);

            $this->markSablonDelivered($billSupplier);

            return $billSupplier;
        });
    }

    public function handleBatchUpdate(string $batch, SaveBillSupplierRequest $request): Collection
    {
        $billSuppliers = BillSupplier::where('batch', $batch)->with('sablon')->get();

        $sablonIds = array_map('intval', $request->input('sablon_ids', []));

        $billSuppliers->each(function (BillSupplier $billSupplier) use ($sablonIds) {
            if (! in_array($billSupplier->sablon_id, $sablonIds)) {
                $billSupplier->delete();
            }
        });

        $existingSablonIds = $billSuppliers
            ->filter(fn($bs) => in_array($bs->sablon_id, $sablonIds))
            ->pluck('sablon_id')
            ->all();

        $newSablonIds = array_diff($sablonIds, $existingSablonIds);

        if (! empty($newSablonIds)) {
            Sablon::whereIn('id', $newSablonIds)->get()->each(function ($sablon) use ($request, $batch) {
                $preview = $this->billSupplierRepository->calculatePreview($sablon);

                $billSupplier = BillSupplier::create([
                    'supplier_id'       => $sablon->supplier_id,
                    'sablon_id'         => $sablon->id,
                    'price_supplier_id' => $preview['price_supplier_id'],
                    'batch'             => $batch,
                    'total_fee'         => $preview['total_fee'],
                    'date_bill'         => $request->input('date_bill'),
                    'is_paid'           => $request->boolean('is_paid'),
                    'is_delivered'      => $request->boolean('is_delivered'),
                    'notes'             => $request->input('notes'),
                ]);

                $this->markSablonDelivered($billSupplier);
            });
        }

        BillSupplier::where('batch', $batch)
            ->whereIn('sablon_id', $existingSablonIds)
            ->get()
            ->each(function (BillSupplier $billSupplier) use ($request) {
                $preview = $this->billSupplierRepository->calculatePreview($billSupplier->sablon);

                $billSupplier->update([
                    'total_fee'         => $preview['total_fee'],
                    'price_supplier_id' => $preview['price_supplier_id'],
                    'date_bill'         => $request->input('date_bill'),
                    'is_paid'           => $request->boolean('is_paid'),
                    'is_delivered'      => $request->boolean('is_delivered'),
                    'notes'             => $request->input('notes'),
                ]);

                $this->markSablonDelivered($billSupplier);
            });

        return BillSupplier::where('batch', $batch)->with('sablon')->get();
    }

    public function markSablonDelivered(BillSupplier $billSupplier): void
    {
        if (! $billSupplier->is_delivered) {
            return;
        }

        $sablon = $billSupplier->sablon;

        if (! $sablon || $sablon->status === StatusSablonEnum::DELIVERED) {
            return;
        }

        $sablon->update(['status' => StatusSablonEnum::DELIVERED]);
    }
}
