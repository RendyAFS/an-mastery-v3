<?php

namespace App\Actions\BillSupplier;

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

            return BillSupplier::create([
                'supplier_id'       => $sablon->supplier_id,
                'sablon_id'         => $sablon->id,
                'price_supplier_id' => $preview['price_supplier_id'],
                'batch'             => $batch,
                'total_fee'         => $preview['total_fee'],
                'date_bill'         => $request->input('date_bill'),
                'is_paid'           => $request->boolean('is_paid'),
                'notes'             => $request->input('notes'),
            ]);
        });
    }

    public function handleBatchUpdate(string $batch, SaveBillSupplierRequest $request): Collection
    {
        $billSuppliers = BillSupplier::where('batch', $batch)
            ->with('sablon')
            ->get();

        $billSuppliers->each(function (BillSupplier $billSupplier) use ($request) {
            $preview = $this->billSupplierRepository->calculatePreview($billSupplier->sablon);

            $billSupplier->update([
                'total_fee'         => $preview['total_fee'],
                'price_supplier_id' => $preview['price_supplier_id'],
                'date_bill'         => $request->input('date_bill'),
                'is_paid'           => $request->boolean('is_paid'),
                'notes'             => $request->input('notes'),
            ]);
        });

        return $billSuppliers->fresh();
    }
}
